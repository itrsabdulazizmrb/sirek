<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelamar extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Check if user is logged in and is an applicant
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'pelamar') {
            redirect('auth');
        }

        // Load models
        $this->load->model('model_pengguna');
        $this->load->model('model_pelamar');
        $this->load->model('model_lowongan');
        $this->load->model('model_lamaran');
        $this->load->model('model_penilaian');
        $this->load->model('model_dokumen');
        $this->load->model('model_notifikasi');

        // Load libraries
        $this->load->library('upload');
        $this->load->library('form_validation');

        // Create uploads directory if it doesn't exist
        if (!is_dir('./uploads/documents')) {
            mkdir('./uploads/documents', 0777, TRUE);
        }
    }

    public function index() {
        redirect('pelamar/dasbor');
    }

    public function dasbor() {
        // Get applicant's applications
        $user_id = $this->session->userdata('user_id');
        $data['applications'] = $this->model_lamaran->dapatkan_lamaran_pelamar($user_id);

        // Get recommended jobs
        $data['recommended_jobs'] = $this->model_lowongan->dapatkan_lowongan_rekomendasi($user_id, 5);

        // Get profile completion percentage
        $data['profile_completion'] = $this->model_pelamar->dapatkan_persentase_kelengkapan_profil($user_id);

        // Load views
        $data['title'] = 'Dasbor Pelamar';
        $this->load->view('templates/applicant_header', $data);
        $this->load->view('pelamar/dasbor', $data);
        $this->load->view('templates/applicant_footer');
    }

    public function profil() {
        // Get applicant profile
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->model_pengguna->dapatkan_pengguna($user_id);
        $data['profile'] = $this->model_pelamar->dapatkan_profil($user_id);
        $data['documents'] = $this->model_dokumen->dapatkan_dokumen_pelamar($user_id);

        // Get default document types
        $default_docs = $this->model_dokumen->dapatkan_dokumen_default();
        $data['document_types'] = [];
        foreach ($default_docs as $doc) {
            $data['document_types'][$doc['jenis_dokumen']] = $doc;
        }

        // Get profile completion percentage
        $data['profile_completion'] = $this->model_pelamar->dapatkan_persentase_kelengkapan_profil($user_id);

        // Form validation rules
        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('phone', 'Telepon', 'trim|required');
        $this->form_validation->set_rules('address', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('date_of_birth', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('education', 'Pendidikan', 'trim|required');
        $this->form_validation->set_rules('experience', 'Pengalaman', 'trim|required');
        $this->form_validation->set_rules('skills', 'Keahlian', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Profil Saya';
            $this->load->view('templates/applicant_header', $data);
            $this->load->view('pelamar/profil', $data);
            $this->load->view('templates/applicant_footer');
        } else {
            // Get form data
            $user_data = array(
                'nama_lengkap' => $this->input->post('full_name'),
                'email' => $this->input->post('email'),
                'telepon' => $this->input->post('phone'),
                'alamat' => $this->input->post('address')
            );

            $profile_data = array(
                'tanggal_lahir' => $this->input->post('date_of_birth'),
                'jenis_kelamin' => $this->input->post('gender'),
                'pendidikan' => $this->input->post('education'),
                'pengalaman' => $this->input->post('experience'),
                'keahlian' => $this->input->post('skills'),
                'url_linkedin' => $this->input->post('linkedin_url'),
                'url_portofolio' => $this->input->post('portfolio_url')
            );

            // Handle CV upload
            if (!empty($_FILES['resume']['name'])) {
                // Make sure the directory exists and is writable
                $upload_path_full = FCPATH . 'uploads/cv/';
                if (!is_dir($upload_path_full)) {
                    mkdir($upload_path_full, 0777, true);
                }

                $config['upload_path'] = 'uploads/cv';
                $config['allowed_types'] = 'pdf|doc|docx';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'cv_' . $user_id . '_' . time();
                $config['encrypt_name'] = FALSE;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('resume')) {
                    $upload_data = $this->upload->data();
                    $profile_data['cv'] = $upload_data['file_name'];

                    // Also save as document in dokumen_pelamar
                    $document_data = [
                        'id_pengguna' => $user_id,
                        'jenis_dokumen' => 'cv',
                        'nama_dokumen' => 'Curriculum Vitae (CV)',
                        'nama_file' => $upload_data['file_name'],
                        'ukuran_file' => $upload_data['file_size'],
                        'tipe_file' => $upload_data['file_type']
                    ];

                    // Check if document already exists
                    $existing_doc = $this->model_dokumen->dapatkan_dokumen_pelamar_by_jenis($user_id, 'cv');
                    if ($existing_doc) {
                        $this->model_dokumen->perbarui_dokumen_pelamar($existing_doc->id, $document_data);
                    } else {
                        $this->model_dokumen->tambah_dokumen_pelamar($document_data);
                    }
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengunggah CV: ' . $this->upload->display_errors());
                    redirect('pelamar/profil');
                }
            }

            // Handle profile picture upload
            if ($_FILES['profile_picture']['name']) {
                // Make sure the directory exists and is writable
                $upload_path_full = FCPATH . 'uploads/profile_pictures/';
                if (!is_dir($upload_path_full)) {
                    mkdir($upload_path_full, 0777, true);
                }

                $config['upload_path'] = 'uploads/profile_pictures';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 1024; // 1MB
                $config['file_name'] = 'profile_' . $user_id . '_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('profile_picture')) {
                    $upload_data = $this->upload->data();
                    $user_data['foto_profil'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('pelamar/profil');
                }
            }

            // Handle document uploads
            $default_docs = $this->model_dokumen->dapatkan_dokumen_default();
            foreach ($default_docs as $doc_type) {
                $field_name = 'document_' . $doc_type['jenis_dokumen'];

                // Skip CV as it's handled separately
                if ($doc_type['jenis_dokumen'] == 'cv') {
                    continue;
                }

                if ($_FILES[$field_name]['name']) {
                    // Configure upload
                    $upload_path_full = FCPATH . 'uploads/documents/';
                    if (!is_dir($upload_path_full)) {
                        mkdir($upload_path_full, 0777, true);
                    }

                    $config = [
                        'upload_path' => 'uploads/documents',
                        'allowed_types' => $doc_type['format_diizinkan'],
                        'max_size' => $doc_type['ukuran_maksimal'],
                        'file_name' => $doc_type['jenis_dokumen'] . '_' . $user_id . '_' . time()
                    ];

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload($field_name)) {
                        $upload_data = $this->upload->data();

                        // Save document data
                        $document_data = [
                            'id_pengguna' => $user_id,
                            'jenis_dokumen' => $doc_type['jenis_dokumen'],
                            'nama_dokumen' => $doc_type['nama_dokumen'],
                            'nama_file' => $upload_data['file_name'],
                            'ukuran_file' => $upload_data['file_size'],
                            'tipe_file' => $upload_data['file_type']
                        ];

                        // Check if document already exists
                        $existing_doc = $this->model_dokumen->dapatkan_dokumen_pelamar_by_jenis($user_id, $doc_type['jenis_dokumen']);
                        if ($existing_doc) {
                            // Delete old file
                            if (file_exists(FCPATH . 'uploads/documents/' . $existing_doc->nama_file)) {
                                unlink(FCPATH . 'uploads/documents/' . $existing_doc->nama_file);
                            }
                            $this->model_dokumen->perbarui_dokumen_pelamar($existing_doc->id, $document_data);
                        } else {
                            $this->model_dokumen->tambah_dokumen_pelamar($document_data);
                        }
                    } else {
                        $this->session->set_flashdata('error', $doc_type['nama_dokumen'] . ': ' . $this->upload->display_errors('', ''));
                    }
                }
            }

            // Update user and profile data
            $this->model_pengguna->perbarui_pengguna($user_id, $user_data);
            $this->model_pelamar->perbarui_profil($user_id, $profile_data);

            // Update session data
            $this->session->set_userdata('full_name', $user_data['nama_lengkap']);
            $this->session->set_userdata('email', $user_data['email']);

            // Show success message
            $this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
            redirect('pelamar/profil');
        }
    }

    public function lamaran() {
        // Get applicant's applications
        $user_id = $this->session->userdata('user_id');
        $data['applications'] = $this->model_lamaran->dapatkan_lamaran_pelamar($user_id);

        // Load views
        $data['title'] = 'Lamaran Saya';
        $this->load->view('templates/applicant_header', $data);
        $this->load->view('pelamar/lamaran', $data);
        $this->load->view('templates/applicant_footer');
    }

    public function lamar($job_id) {
        // Get job details
        $data['job'] = $this->model_lowongan->dapatkan_lowongan($job_id);

        // If job not found or not active, show 404
        if (!$data['job'] || $data['job']->status != 'aktif') {
            show_404();
        }

        // Check if already applied
        $user_id = $this->session->userdata('user_id');
        if ($this->model_lamaran->sudah_melamar($user_id, $job_id)) {
            $this->session->set_flashdata('error', 'Anda sudah melamar untuk lowongan ini.');
            redirect('lowongan/detail/' . $job_id);
        }

        // Get applicant profile
        $data['profile'] = $this->model_pelamar->dapatkan_profil($user_id);

        // Get applicant documents
        $data['documents'] = $this->model_dokumen->dapatkan_dokumen_pelamar($user_id);

        // Get document requirements for this job
        $data['document_requirements'] = $this->model_dokumen->dapatkan_dokumen_lowongan($job_id);

        // Form validation rules
        $this->form_validation->set_rules('cover_letter', 'Surat Lamaran', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Lamar Lowongan';
            $this->load->view('templates/applicant_header', $data);
            $this->load->view('pelamar/lamar', $data);
            $this->load->view('templates/applicant_footer');
        } else {
            // Get form data
            $application_data = array(
                'id_pekerjaan' => $job_id,
                'id_pelamar' => $user_id,
                'surat_lamaran' => $this->input->post('cover_letter')
            );

            // Handle CV upload (for backward compatibility)
            if ($_FILES['resume']['name']) {
                // Make sure the directory exists and is writable
                $upload_path_full = FCPATH . 'uploads/cv/';
                if (!is_dir($upload_path_full)) {
                    mkdir($upload_path_full, 0777, true);
                }

                $config['upload_path'] = realpath($upload_path_full) . '/';
                $config['allowed_types'] = 'pdf|doc|docx';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'cv_' . $user_id . '_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('resume')) {
                    $upload_data = $this->upload->data();
                    $application_data['cv'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengunggah CV: ' . $this->upload->display_errors());
                    redirect('pelamar/lamar/' . $job_id);
                }
            } else if ($data['profile']->cv) {
                // Use existing resume from profile
                $application_data['cv'] = $data['profile']->cv;
            } else {
                // Check if CV is required in document requirements
                $cv_required = false;
                foreach ($data['document_requirements'] as $req) {
                    if ($req->jenis_dokumen == 'cv' && $req->wajib == 1) {
                        $cv_required = true;
                        break;
                    }
                }

                if ($cv_required) {
                    $this->session->set_flashdata('error', 'Silakan unggah CV Anda.');
                    redirect('pelamar/lamar/' . $job_id);
                }
            }

            // Insert application data
            $application_id = $this->model_lamaran->tambah_lamaran($application_data);

            if ($application_id) {
                // Process document uploads
                $upload_errors = [];
                $missing_required_docs = [];

                // Check if there are document requirements
                if (!empty($data['document_requirements'])) {
                    foreach ($data['document_requirements'] as $req) {
                        $field_name = 'document_' . $req->id;
                        $use_existing_cv_field = 'use_existing_cv_' . $req->id;

                        // Check if it's a CV document and user wants to use existing CV
                        if ($req->jenis_dokumen == 'cv' && $data['profile']->cv && $this->input->post($use_existing_cv_field) == '1') {
                            // Use existing CV from profile
                            $document_data = [
                                'id_lamaran' => $application_id,
                                'id_dokumen_lowongan' => $req->id,
                                'jenis_dokumen' => $req->jenis_dokumen,
                                'nama_file' => $data['profile']->cv,
                                'ukuran_file' => 0, // We don't know the exact size
                                'tipe_file' => 'application/pdf' // Assume PDF as default
                            ];

                            $this->model_dokumen->tambah_dokumen_lamaran($document_data);
                            continue; // Skip to next document requirement
                        }

                        // Check if user wants to use existing document from profile
                        $use_existing_doc_field = 'use_existing_doc_' . $req->id;
                        if ($this->input->post($use_existing_doc_field)) {
                            $existing_doc_id = $this->input->post($use_existing_doc_field);
                            $existing_doc = $this->model_dokumen->dapatkan_dokumen_pelamar_by_id($existing_doc_id);

                            if ($existing_doc && $existing_doc->id_pengguna == $user_id) {
                                // Use existing document from profile
                                $document_data = [
                                    'id_lamaran' => $application_id,
                                    'id_dokumen_lowongan' => $req->id,
                                    'jenis_dokumen' => $req->jenis_dokumen,
                                    'nama_file' => $existing_doc->nama_file,
                                    'ukuran_file' => $existing_doc->ukuran_file,
                                    'tipe_file' => $existing_doc->tipe_file
                                ];

                                $this->model_dokumen->tambah_dokumen_lamaran($document_data);
                                continue; // Skip to next document requirement
                            }
                        }

                        // Check if file was uploaded
                        if (isset($_FILES[$field_name]) && $_FILES[$field_name]['name']) {
                            // Configure upload
                            $upload_path_full = FCPATH . 'uploads/documents/';
                            if (!is_dir($upload_path_full)) {
                                mkdir($upload_path_full, 0777, true);
                            }

                            $config = [
                                'upload_path' => 'uploads/documents',
                                'allowed_types' => $req->format_diizinkan,
                                'max_size' => $req->ukuran_maksimal,
                                'file_name' => $req->jenis_dokumen . '_' . $user_id . '_' . time()
                            ];

                            $this->upload->initialize($config);

                            if ($this->upload->do_upload($field_name)) {
                                $upload_data = $this->upload->data();

                                // Save document data
                                $document_data = [
                                    'id_lamaran' => $application_id,
                                    'id_dokumen_lowongan' => $req->id,
                                    'jenis_dokumen' => $req->jenis_dokumen,
                                    'nama_file' => $upload_data['file_name'],
                                    'ukuran_file' => $upload_data['file_size'],
                                    'tipe_file' => $upload_data['file_type']
                                ];

                                $this->model_dokumen->tambah_dokumen_lamaran($document_data);
                            } else {
                                $upload_errors[] = $req->nama_dokumen . ': ' . $this->upload->display_errors('', '');
                            }
                        } else if ($req->wajib == 1) {
                            // Document is required but not uploaded
                            // Skip CV if using existing CV
                            if (!($req->jenis_dokumen == 'cv' && $data['profile']->cv && $this->input->post($use_existing_cv_field) == '1')) {
                                $missing_required_docs[] = $req->nama_dokumen;
                            }
                        }
                    }
                }

                // Check for missing required documents
                if (!empty($missing_required_docs)) {
                    // Delete the application since required documents are missing
                    $this->model_lamaran->hapus_lamaran($application_id);

                    $this->session->set_flashdata('error', 'Dokumen wajib berikut belum diunggah: ' . implode(', ', $missing_required_docs));
                    redirect('pelamar/lamar/' . $job_id);
                }

                // Check for upload errors
                if (!empty($upload_errors)) {
                    // Delete the application since there were upload errors
                    $this->model_lamaran->hapus_lamaran($application_id);

                    $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengunggah dokumen: ' . implode('; ', $upload_errors));
                    redirect('pelamar/lamar/' . $job_id);
                }

                // Create notification for new application
                $this->buat_notifikasi_lamaran_baru($application_id);

                // Show success message
                $this->session->set_flashdata('success', 'Lamaran Anda berhasil dikirim.');
                redirect('pelamar/lamaran');
            } else {
                // If insertion fails, show error message
                $this->session->set_flashdata('error', 'Gagal mengirim lamaran. Silakan coba lagi.');
                redirect('pelamar/lamar/' . $job_id);
            }
        }
    }

    public function detail_lamaran($id) {
        // Get application details
        $user_id = $this->session->userdata('user_id');
        $data['application'] = $this->model_lamaran->dapatkan_lamaran($id);

        // If application not found or not owned by current user, show 404
        if (!$data['application'] || $data['application']->id_pelamar != $user_id) {
            show_404();
        }

        // Get job details
        $data['job'] = $this->model_lowongan->dapatkan_lowongan($data['application']->id_pekerjaan);

        // Get assessment results
        $data['assessments'] = $this->model_penilaian->dapatkan_penilaian_pelamar($id);

        // Get uploaded documents
        $data['documents'] = $this->model_dokumen->dapatkan_dokumen_lamaran($id);

        // Load views
        $data['title'] = 'Detail Lamaran';
        $this->load->view('templates/applicant_header', $data);
        $this->load->view('pelamar/detail_lamaran', $data);
        $this->load->view('templates/applicant_footer');
    }

    // Download dokumen lamaran
    public function download_dokumen($id) {
        // Get document details
        $document = $this->model_dokumen->dapatkan_dokumen_lamaran_by_id($id);

        // If document not found, show 404
        if (!$document) {
            show_404();
        }

        // Check if document belongs to current user
        $user_id = $this->session->userdata('user_id');
        $application = $this->model_lamaran->dapatkan_lamaran($document->id_lamaran);

        if (!$application || $application->id_pelamar != $user_id) {
            show_404();
        }

        // Set file path based on document type
        $file_path = '';
        if ($document->jenis_dokumen == 'cv') {
            $file_path = './uploads/cv/' . $document->nama_file;
        } else {
            $file_path = './uploads/documents/' . $document->nama_file;
        }

        // Check if file exists
        if (!file_exists($file_path)) {
            $this->session->set_flashdata('error', 'File dokumen tidak ditemukan.');
            redirect('pelamar/detail_lamaran/' . $document->id_lamaran);
        }

        // Get file info
        $file_info = pathinfo($file_path);
        $file_name = $document->jenis_dokumen . '_' . time() . '.' . $file_info['extension'];

        // Force download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    }

    // Download dokumen pelamar
    public function download_dokumen_pelamar($id) {
        // Get document details
        $document = $this->model_dokumen->dapatkan_dokumen_pelamar_by_id($id);

        // If document not found, show 404
        if (!$document) {
            show_404();
        }

        // Check if document belongs to current user
        $user_id = $this->session->userdata('user_id');
        if ($document->id_pengguna != $user_id) {
            show_404();
        }

        // Set file path
        $file_path = '';
        if ($document->jenis_dokumen == 'cv') {
            $file_path = './uploads/cv/' . $document->nama_file;
        } else {
            $file_path = './uploads/documents/' . $document->nama_file;
        }

        // Check if file exists
        if (!file_exists($file_path)) {
            $this->session->set_flashdata('error', 'File dokumen tidak ditemukan.');
            redirect('pelamar/profil');
        }

        // Get file info
        $file_info = pathinfo($file_path);
        $file_name = $document->jenis_dokumen . '_' . time() . '.' . $file_info['extension'];

        // Force download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    }

    // Hapus dokumen pelamar
    public function hapus_dokumen_pelamar($id) {
        // Get document details
        $document = $this->model_dokumen->dapatkan_dokumen_pelamar_by_id($id);

        // If document not found, show 404
        if (!$document) {
            show_404();
        }

        // Check if document belongs to current user
        $user_id = $this->session->userdata('user_id');
        if ($document->id_pengguna != $user_id) {
            show_404();
        }

        // Set file path
        $file_path = '';
        if ($document->jenis_dokumen == 'cv') {
            $file_path = './uploads/cv/' . $document->nama_file;

            // Update profile to remove CV reference
            $this->model_pelamar->perbarui_profil($user_id, ['cv' => null]);
        } else {
            $file_path = './uploads/documents/' . $document->nama_file;
        }

        // Delete file if exists
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete document record
        $this->model_dokumen->hapus_dokumen_pelamar($id);

        // Show success message
        $this->session->set_flashdata('success', 'Dokumen berhasil dihapus.');
        redirect('pelamar/profil');
    }

    public function penilaian() {
        // Get applicant's assessments
        $user_id = $this->session->userdata('user_id');
        $data['assessments'] = $this->model_penilaian->dapatkan_semua_penilaian_pelamar($user_id);

        // Load views
        $data['title'] = 'Penilaian Saya';
        $this->load->view('templates/applicant_header', $data);
        $this->load->view('pelamar/penilaian', $data);
        $this->load->view('templates/applicant_footer');
    }

    public function ikuti_penilaian($assessment_id, $application_id) {
        // Get assessment details
        $data['assessment'] = $this->model_penilaian->dapatkan_penilaian($assessment_id);

        // If assessment not found, show 404
        if (!$data['assessment']) {
            show_404();
        }

        // Get applicant assessment
        $user_id = $this->session->userdata('user_id');
        $data['applicant_assessment'] = $this->model_penilaian->dapatkan_penilaian_pelamar_spesifik($application_id, $assessment_id);

        // If applicant assessment not found or not owned by current user, show 404
        if (!$data['applicant_assessment'] || $data['applicant_assessment']->id_pelamar != $user_id) {
            show_404();
        }

        // Check if assessment uses CAT mode
        $cat_settings = $this->model_penilaian->cek_mode_cat($assessment_id);
        $data['cat_mode'] = $cat_settings->mode_cat;
        $data['acak_soal'] = $cat_settings->acak_soal;

        // If status is 'belum_mulai', show confirmation page
        if ($data['applicant_assessment']->status == 'belum_mulai') {
            // Get assessment questions for info
            $data['questions'] = $this->model_penilaian->dapatkan_soal_penilaian($assessment_id);

            // Load confirmation page
            $data['title'] = 'Konfirmasi Mulai Ujian';
            $data['application_id'] = $application_id;
            $this->load->view('templates/applicant_header', $data);
            $this->load->view('pelamar/konfirmasi_mulai_ujian', $data);
            $this->load->view('templates/applicant_footer');
        } else {
            // If already started, redirect to appropriate interface
            if ($data['cat_mode']) {
                redirect('pelamar/cat-penilaian/' . $assessment_id . '/' . $application_id . '/1');
            } else {
                redirect('pelamar/ikuti-ujian/' . $assessment_id . '/' . $application_id);
            }
        }
    }

    // Method baru untuk halaman ujian setelah dimulai
    public function ikuti_ujian($assessment_id, $application_id) {
        // Get assessment details
        $data['assessment'] = $this->model_penilaian->dapatkan_penilaian($assessment_id);

        // If assessment not found, show 404
        if (!$data['assessment']) {
            show_404();
        }

        // Get applicant assessment
        $user_id = $this->session->userdata('user_id');
        $data['applicant_assessment'] = $this->model_penilaian->dapatkan_penilaian_pelamar_spesifik($application_id, $assessment_id);

        // If applicant assessment not found or not owned by current user, show 404
        if (!$data['applicant_assessment'] || $data['applicant_assessment']->id_pelamar != $user_id) {
            show_404();
        }

        // If status is still 'belum_mulai', redirect to confirmation
        if ($data['applicant_assessment']->status == 'belum_mulai') {
            redirect('pelamar/ikuti-penilaian/' . $assessment_id . '/' . $application_id);
        }

        // Get assessment questions (traditional mode)
        $data['questions'] = $this->model_penilaian->dapatkan_soal_penilaian($assessment_id);

        // Load views
        $data['title'] = 'Ikuti Penilaian';
        $data['application_id'] = $application_id;
        $this->load->view('templates/applicant_header', $data);
        $this->load->view('pelamar/ikuti_penilaian', $data);
        $this->load->view('templates/applicant_footer');
    }

    public function cat_penilaian($assessment_id, $application_id, $question_number = 1) {
        // Get assessment details
        $data['assessment'] = $this->model_penilaian->dapatkan_penilaian($assessment_id);

        // If assessment not found, show 404
        if (!$data['assessment']) {
            show_404();
        }

        // Get applicant assessment
        $user_id = $this->session->userdata('user_id');
        $data['applicant_assessment'] = $this->model_penilaian->dapatkan_penilaian_pelamar_spesifik($application_id, $assessment_id);

        // If applicant assessment not found or not owned by current user, show 404
        if (!$data['applicant_assessment'] || $data['applicant_assessment']->id_pelamar != $user_id) {
            show_404();
        }

        // Check if assessment uses CAT mode
        $cat_settings = $this->model_penilaian->cek_mode_cat($assessment_id);
        if (!$cat_settings->mode_cat) {
            // Redirect to traditional mode if CAT is not enabled
            redirect('pelamar/ikuti-penilaian/' . $assessment_id . '/' . $application_id);
        }

        // If status is still 'belum_mulai', redirect to confirmation
        if ($data['applicant_assessment']->status == 'belum_mulai') {
            redirect('pelamar/ikuti-penilaian/' . $assessment_id . '/' . $application_id);
        }

        // Initialize question order if needed
        $total_questions = $this->model_penilaian->dapatkan_total_soal_cat($data['applicant_assessment']->id);
        if ($total_questions == 0) {
            $this->model_penilaian->buat_urutan_soal_acak($data['applicant_assessment']->id);
            $total_questions = $this->model_penilaian->dapatkan_total_soal_cat($data['applicant_assessment']->id);
        }

        // Validate question number
        if ($question_number < 1 || $question_number > $total_questions) {
            $question_number = 1;
        }

        // Get current question
        $data['current_question'] = $this->model_penilaian->dapatkan_soal_cat_berdasarkan_urutan($data['applicant_assessment']->id, $question_number);
        $data['question_number'] = $question_number;
        $data['total_questions'] = $total_questions;
        $data['application_id'] = $application_id;

        // Get question status for navigation
        $data['question_status'] = $this->model_penilaian->dapatkan_status_jawaban_cat($data['applicant_assessment']->id);

        // Load CAT views
        $data['title'] = 'Ujian CAT - ' . $data['assessment']->judul;
        $this->load->view('pelamar/cat_penilaian', $data);
    }

    public function simpan_jawaban_cat() {
        // Disable CSRF for this method
        $this->security->csrf_verify = FALSE;

        // Validasi AJAX request
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $applicant_assessment_id = $this->input->post('applicant_assessment_id');
        $question_id = $this->input->post('question_id');
        $answer_type = $this->input->post('answer_type');

        // Validasi input
        if (!$applicant_assessment_id || !$question_id || !$answer_type) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
            return;
        }

        $answer_data = array(
            'id_penilaian_pelamar' => $applicant_assessment_id,
            'id_soal' => $question_id,
            'diperbarui_pada' => date('Y-m-d H:i:s')
        );

        // Handle different answer types
        if ($answer_type == 'pilihan_ganda') {
            $selected_option = $this->input->post('selected_option');
            if ($selected_option) {
                $answer_data['id_pilihan_terpilih'] = $selected_option;
                $answer_data['teks_jawaban'] = null;
                $answer_data['unggah_file'] = null;
            }
        } elseif ($answer_type == 'esai') {
            $text_answer = $this->input->post('text_answer');
            if ($text_answer) {
                $answer_data['teks_jawaban'] = $text_answer;
                $answer_data['id_pilihan_terpilih'] = null;
                $answer_data['unggah_file'] = null;
            }
        }

        // Save answer
        $result = $this->model_penilaian->simpan_jawaban_cat($answer_data);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Jawaban berhasil disimpan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan jawaban']);
        }
    }

    public function tandai_ragu_cat() {
        // Disable CSRF for this method
        $this->security->csrf_verify = FALSE;

        // Validasi AJAX request
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $applicant_assessment_id = $this->input->post('applicant_assessment_id');
        $question_id = $this->input->post('question_id');
        $ragu = $this->input->post('ragu') ? 1 : 0;

        // Validasi input
        if (!$applicant_assessment_id || !$question_id) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
            return;
        }

        $result = $this->model_penilaian->tandai_soal_ragu($applicant_assessment_id, $question_id, $ragu);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Status ragu berhasil diperbarui']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status ragu']);
        }
    }

    public function dapatkan_status_navigasi_cat() {
        // Disable CSRF for this method
        $this->security->csrf_verify = FALSE;

        // Validasi AJAX request
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $applicant_assessment_id = $this->input->post('applicant_assessment_id');

        if (!$applicant_assessment_id) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
            return;
        }

        $status = $this->model_penilaian->dapatkan_status_jawaban_cat($applicant_assessment_id);
        echo json_encode(['status' => 'success', 'data' => $status]);
    }

    public function get_question_cat() {
        // Disable CSRF for this method
        $this->security->csrf_verify = FALSE;

        // Validasi AJAX request
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $applicant_assessment_id = $this->input->post('applicant_assessment_id');
        $question_number = $this->input->post('question_number');

        if (!$applicant_assessment_id || !$question_number) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
            return;
        }

        // Validasi bahwa penilaian_pelamar milik user yang sedang login
        $user_id = $this->session->userdata('user_id');
        $applicant_assessment = $this->model_penilaian->dapatkan_penilaian_pelamar_by_id($applicant_assessment_id);

        if (!$applicant_assessment) {
            echo json_encode(['status' => 'error', 'message' => 'Penilaian tidak ditemukan']);
            return;
        }

        // Cek apakah penilaian ini milik user yang sedang login
        $lamaran = $this->model_lamaran->dapatkan_lamaran($applicant_assessment->id_lamaran);
        if (!$lamaran || $lamaran->id_pelamar != $user_id) {
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak']);
            return;
        }

        // Get question data
        $question = $this->model_penilaian->dapatkan_soal_cat_berdasarkan_urutan($applicant_assessment_id, $question_number);

        if (!$question) {
            echo json_encode(['status' => 'error', 'message' => 'Soal tidak ditemukan']);
            return;
        }

        echo json_encode(['status' => 'success', 'question' => $question]);
    }

    public function log_security_violation() {
        // Disable CSRF for this method
        $this->security->csrf_verify = FALSE;

        // Validasi AJAX request
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $applicant_assessment_id = $this->input->post('applicant_assessment_id');
        $violation = $this->input->post('violation');
        $timestamp = $this->input->post('timestamp');

        if (!$applicant_assessment_id || !$violation) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
            return;
        }

        // Validasi bahwa penilaian_pelamar milik user yang sedang login
        $user_id = $this->session->userdata('user_id');
        $applicant_assessment = $this->model_penilaian->dapatkan_penilaian_pelamar_by_id($applicant_assessment_id);

        if (!$applicant_assessment) {
            echo json_encode(['status' => 'error', 'message' => 'Penilaian tidak ditemukan']);
            return;
        }

        // Cek apakah penilaian ini milik user yang sedang login
        $lamaran = $this->model_lamaran->dapatkan_lamaran($applicant_assessment->id_lamaran);
        if (!$lamaran || $lamaran->id_pelamar != $user_id) {
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak']);
            return;
        }

        // Log ke database atau file
        $log_data = array(
            'id_penilaian_pelamar' => $applicant_assessment_id,
            'id_pelamar' => $user_id,
            'violation_type' => $violation,
            'timestamp' => date('Y-m-d H:i:s', $timestamp ? $timestamp/1000 : time()),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s')
        );

        // Simpan ke tabel log_security_violations (jika ada) atau log file
        $this->log_security_to_database($log_data);

        echo json_encode(['status' => 'success', 'message' => 'Violation logged']);
    }

    private function log_security_to_database($log_data) {
        try {
            // Coba simpan ke tabel log_security_violations
            $this->db->insert('log_security_violations', $log_data);
        } catch (Exception $e) {
            // Jika tabel tidak ada, simpan ke file log
            $log_message = date('Y-m-d H:i:s') . " - Security Violation: " .
                          "User ID: {$log_data['id_pelamar']}, " .
                          "Assessment ID: {$log_data['id_penilaian_pelamar']}, " .
                          "Violation: {$log_data['violation_type']}, " .
                          "IP: {$log_data['ip_address']}" . PHP_EOL;

            $log_file = APPPATH . 'logs/security_violations_' . date('Y-m-d') . '.log';
            file_put_contents($log_file, $log_message, FILE_APPEND | LOCK_EX);
        }
    }

    public function perbarui_status_penilaian($applicant_assessment_id, $status) {
        // Validasi input
        if (!$applicant_assessment_id || !$status) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
            return;
        }

        // Perbarui status penilaian
        $result = $this->model_penilaian->perbarui_status_penilaian_pelamar($applicant_assessment_id, $status);

        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status']);
        }
    }

    // Method khusus untuk memulai ujian dengan record waktu mulai
    public function mulai_ujian($applicant_assessment_id) {
        // Set headers immediately
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-Type: application/json; charset=utf-8');

        try {
            // Validasi AJAX request (lebih permisif)
            if (!$this->input->is_ajax_request() && !isset($_POST['test_mulai_ujian'])) {
                throw new Exception('Request tidak valid');
            }

            // Validasi input
            if (!$applicant_assessment_id || !is_numeric($applicant_assessment_id)) {
                throw new Exception('Parameter ID tidak valid');
            }

            // Validasi session
            $user_id = $this->session->userdata('user_id');
            if (!$user_id) {
                throw new Exception('Session tidak valid. Silakan login kembali.');
            }

            // Load models
            $this->load->model('model_penilaian');
            $this->load->model('model_lamaran');

            // Validasi ownership
            $applicant_assessment = $this->model_penilaian->dapatkan_penilaian_pelamar_by_id($applicant_assessment_id);

            if (!$applicant_assessment) {
                throw new Exception('Penilaian tidak ditemukan');
            }

            $lamaran = $this->model_lamaran->dapatkan_lamaran($applicant_assessment->id_lamaran);
            if (!$lamaran || $lamaran->id_pelamar != $user_id) {
                throw new Exception('Akses ditolak. Anda tidak memiliki izin untuk ujian ini.');
            }

            // Cek apakah status masih belum_mulai
            if ($applicant_assessment->status != 'belum_mulai') {
                throw new Exception('Ujian sudah dimulai atau selesai. Status saat ini: ' . $applicant_assessment->status);
            }

            // Update status dan waktu mulai
            $update_data = array(
                'status' => 'sedang_mengerjakan',
                'waktu_mulai' => date('Y-m-d H:i:s'),
                'diperbarui_pada' => date('Y-m-d H:i:s')
            );

            $this->db->where('id', $applicant_assessment_id);
            $result = $this->db->update('penilaian_pelamar', $update_data);

            if (!$result) {
                // Cek database error
                $db_error = $this->db->error();
                log_message('error', 'Database error saat memulai ujian: ' . json_encode($db_error));
                throw new Exception('Gagal memperbarui database. Error: ' . $db_error['message']);
            }

            // Verifikasi update berhasil
            $affected_rows = $this->db->affected_rows();
            if ($affected_rows == 0) {
                throw new Exception('Tidak ada data yang diperbarui. Mungkin ID tidak ditemukan.');
            }

            // Log untuk debugging
            log_message('info', 'Ujian dimulai untuk penilaian_pelamar ID: ' . $applicant_assessment_id . ' pada ' . $update_data['waktu_mulai'] . ' oleh user ID: ' . $user_id);

            // Response sukses
            echo json_encode([
                'status' => 'success',
                'message' => 'Ujian berhasil dimulai',
                'waktu_mulai' => $update_data['waktu_mulai'],
                'applicant_assessment_id' => $applicant_assessment_id,
                'affected_rows' => $affected_rows
            ]);
            exit;

        } catch (Exception $e) {
            // Log error
            log_message('error', 'Error saat memulai ujian: ' . $e->getMessage() . ' | User ID: ' . ($user_id ?? 'unknown') . ' | Assessment ID: ' . $applicant_assessment_id);

            // Ensure JSON response even on error
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
                'debug_info' => [
                    'user_id' => $user_id ?? null,
                    'applicant_assessment_id' => $applicant_assessment_id,
                    'timestamp' => date('Y-m-d H:i:s')
                ]
            ]);
            exit;
        } catch (Error $e) {
            // Handle PHP fatal errors
            log_message('error', 'PHP Error saat memulai ujian: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine());

            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
                'debug_info' => [
                    'error_type' => 'PHP Error',
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'timestamp' => date('Y-m-d H:i:s')
                ]
            ]);
            exit;
        }
    }

    // Method test sederhana
    public function test_json() {
        header('Content-Type: application/json; charset=utf-8');
        echo '{"status":"success","message":"JSON test berhasil","timestamp":"' . date('Y-m-d H:i:s') . '"}';
        exit;
    }

    // Method test untuk debug
    public function test_mulai_ujian($applicant_assessment_id = null) {
        // Set headers to prevent caching and ensure JSON
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-Type: application/json; charset=utf-8');

        // Simple test response
        $response = [
            'status' => 'success',
            'message' => 'Test method berhasil',
            'data' => [
                'applicant_assessment_id' => $applicant_assessment_id,
                'timestamp' => date('Y-m-d H:i:s'),
                'user_id' => $this->session->userdata('user_id'),
                'is_ajax' => $this->input->is_ajax_request() ? 'yes' : 'no',
                'method' => $this->input->method(),
                'user_agent' => $this->input->user_agent(),
                'ip_address' => $this->input->ip_address()
            ]
        ];

        echo json_encode($response);
        exit; // Ensure no additional output
    }

    public function kirim_penilaian_cat() {
        // Disable CSRF for this method
        $this->security->csrf_verify = FALSE;

        // Get form data
        $assessment_id = $this->input->post('assessment_id');
        $application_id = $this->input->post('application_id');
        $applicant_assessment_id = $this->input->post('applicant_assessment_id');

        // Validate required data
        if (!$assessment_id || !$application_id || !$applicant_assessment_id) {
            $this->session->set_flashdata('error', 'Data penilaian tidak lengkap.');
            redirect('pelamar/penilaian');
            return;
        }

        // Validate user ownership
        $user_id = $this->session->userdata('user_id');
        $applicant_assessment = $this->model_penilaian->dapatkan_penilaian_pelamar_by_id($applicant_assessment_id);

        if (!$applicant_assessment) {
            $this->session->set_flashdata('error', 'Penilaian tidak ditemukan.');
            redirect('pelamar/penilaian');
            return;
        }

        $lamaran = $this->model_lamaran->dapatkan_lamaran($applicant_assessment->id_lamaran);
        if (!$lamaran || $lamaran->id_pelamar != $user_id) {
            $this->session->set_flashdata('error', 'Akses ditolak.');
            redirect('pelamar/penilaian');
            return;
        }

        // Update applicant assessment status and completion time
        $update_data = array(
            'status' => 'selesai',
            'waktu_selesai' => date('Y-m-d H:i:s'),
            'diperbarui_pada' => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $applicant_assessment_id);
        $this->db->update('penilaian_pelamar', $update_data);

        // Calculate score for CAT assessment
        $score = $this->model_penilaian->hitung_skor_penilaian_pelamar($applicant_assessment_id);

        // Update score
        $this->db->where('id', $applicant_assessment_id);
        $this->db->update('penilaian_pelamar', array('nilai' => $score));

        // Log completion
        $log_data = array(
            'id_penilaian_pelamar' => $applicant_assessment_id,
            'id_pelamar' => $user_id,
            'violation_type' => 'Assessment completed successfully',
            'timestamp' => date('Y-m-d H:i:s'),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->log_security_to_database($log_data);

        // Show success message
        $this->session->set_flashdata('success', 'Ujian CAT berhasil diselesaikan. Skor Anda: ' . $score . '%');
        redirect('pelamar/detail_lamaran/' . $application_id);
    }

    public function kirim_penilaian() {
        // Get form data
        $assessment_id = $this->input->post('assessment_id');
        $application_id = $this->input->post('application_id');
        $applicant_assessment_id = $this->input->post('applicant_assessment_id');

        // Validate required data
        if (!$assessment_id || !$application_id || !$applicant_assessment_id) {
            $this->session->set_flashdata('error', 'Data penilaian tidak lengkap.');
            redirect('pelamar/penilaian');
            return;
        }

        // Update applicant assessment status and completion time
        $this->model_penilaian->perbarui_status_penilaian_pelamar($applicant_assessment_id, 'selesai');

        // Process answers
        $questions = $this->model_penilaian->dapatkan_soal_penilaian($assessment_id);

        foreach ($questions as $question) {
            $answer_data = array(
                'id_penilaian_pelamar' => $applicant_assessment_id,
                'id_soal' => $question->id,
                'teks_jawaban' => null,
                'id_pilihan_terpilih' => null,
                'unggah_file' => null
            );

            // Handle different question types based on Indonesian field names
            if ($question->jenis_soal == 'pilihan_ganda' || $question->jenis_soal == 'multiple_choice') {
                $selected_option = $this->input->post('question_' . $question->id);
                if ($selected_option) {
                    $answer_data['id_pilihan_terpilih'] = $selected_option;
                }
            } else if ($question->jenis_soal == 'benar_salah' || $question->jenis_soal == 'true_false') {
                $selected_option = $this->input->post('question_' . $question->id);
                if ($selected_option) {
                    // For true/false questions, we need to find the correct option ID
                    $this->db->where('id_soal', $question->id);
                    if ($selected_option == 'true') {
                        $this->db->where('teks_pilihan', 'Benar');
                    } else {
                        $this->db->where('teks_pilihan', 'Salah');
                    }
                    $option_query = $this->db->get('pilihan_soal');
                    $option = $option_query->row();

                    if ($option) {
                        $answer_data['id_pilihan_terpilih'] = $option->id;
                    }
                }
            } else if ($question->jenis_soal == 'esai' || $question->jenis_soal == 'essay') {
                $text_answer = $this->input->post('question_' . $question->id);
                if ($text_answer && trim($text_answer) != '') {
                    $answer_data['teks_jawaban'] = trim($text_answer);
                }
            } else if ($question->jenis_soal == 'unggah_file' || $question->jenis_soal == 'file_upload') {
                // Handle file upload
                $upload_path = FCPATH . 'uploads/answers/';
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'pdf|doc|docx|jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'answer_' . $applicant_assessment_id . '_' . $question->id . '_' . time();

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('question_' . $question->id)) {
                    $upload_data = $this->upload->data();
                    $answer_data['unggah_file'] = $upload_data['file_name'];
                }
            }

            // Only insert answer if there's actually an answer provided
            if ($answer_data['id_pilihan_terpilih'] || $answer_data['teks_jawaban'] || $answer_data['unggah_file']) {
                $this->model_penilaian->tambah_jawaban_pelamar($answer_data);
            }
        }

        // Calculate and update score
        $score = $this->model_penilaian->hitung_skor_penilaian_pelamar($applicant_assessment_id);

        // Show success message
        $this->session->set_flashdata('success', 'Penilaian berhasil dikirim. Skor Anda: ' . $score . '%');
        redirect('pelamar/detail_lamaran/' . $application_id);
    }

    public function ubah_password() {
        // Form validation rules
        $this->form_validation->set_rules('current_password', 'Password Saat Ini', 'trim|required');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'trim|required|matches[new_password]');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Ubah Password';
            $this->load->view('templates/applicant_header', $data);
            $this->load->view('pelamar/ubah_password');
            $this->load->view('templates/applicant_footer');
        } else {
            // Get form data
            $user_id = $this->session->userdata('user_id');
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');

            // Check if current password is correct
            $user = $this->model_pengguna->dapatkan_pengguna($user_id);

            if (password_verify($current_password, $user->password)) {
                // Update password
                $this->model_pengguna->perbarui_password($user_id, password_hash($new_password, PASSWORD_DEFAULT));

                // Show success message
                $this->session->set_flashdata('success', 'Password berhasil diubah.');
                redirect('pelamar/dasbor');
            } else {
                // If current password is incorrect, show error message
                $this->session->set_flashdata('error', 'Password saat ini tidak benar.');
                redirect('pelamar/ubah_password');
            }
        }
    }

    // Helper method: Buat notifikasi untuk lamaran baru
    private function buat_notifikasi_lamaran_baru($id_lamaran) {
        // Get application details
        $lamaran = $this->model_lamaran->dapatkan_lamaran($id_lamaran);
        if (!$lamaran) return false;

        // Get job details
        $lowongan = $this->model_lowongan->dapatkan_lowongan($lamaran->id_pekerjaan);
        if (!$lowongan) return false;

        // Get applicant details
        $pelamar = $this->model_pengguna->dapatkan_pengguna($lamaran->id_pelamar);
        if (!$pelamar) return false;

        // Get all admin users
        $admins = $this->model_pengguna->dapatkan_pengguna_berdasarkan_peran('admin');

        $notification_data = array(
            'judul' => 'Lamaran Baru Diterima',
            'pesan' => "Lamaran baru telah diterima untuk posisi {$lowongan->judul} dari {$pelamar->nama_lengkap}. Silakan tinjau dan proses lamaran ini.",
            'jenis' => 'lamaran_baru',
            'prioritas' => 'normal',
            'id_referensi' => $id_lamaran,
            'tabel_referensi' => 'lamaran_pekerjaan',
            'url_aksi' => 'admin/detail_lamaran/' . $id_lamaran,
            'dibuat_oleh' => null
        );

        // Send notification to all admins
        $admin_ids = array_column($admins, 'id');
        return $this->model_notifikasi->buat_notifikasi_massal($notification_data, $admin_ids);
    }
}

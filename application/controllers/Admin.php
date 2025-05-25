<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Check if user is logged in and is admin or staff
        if (!$this->session->userdata('logged_in') ||
            ($this->session->userdata('role') != 'admin' && $this->session->userdata('role') != 'staff')) {
            redirect('auth');
        }

        // Load models
        $this->load->model('model_pengguna');
        $this->load->model('model_lowongan');
        $this->load->model('model_lamaran');
        $this->load->model('model_penilaian');
        $this->load->model('model_blog');
        $this->load->model('model_kategori');
        $this->load->model('model_pelamar');
        $this->load->model('model_dokumen');

        // Load libraries
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('upload');
    }

    public function index() {
        redirect('admin/dasbor');
    }

    public function dasbor() {
        // Get dashboard statistics
        $data['total_jobs'] = $this->model_lowongan->hitung_lowongan();
        $data['active_jobs'] = $this->model_lowongan->hitung_lowongan_aktif();
        $data['total_applications'] = $this->model_lamaran->hitung_lamaran();
        $data['new_applications'] = $this->model_lamaran->hitung_lamaran_baru();
        $data['total_users'] = $this->model_pengguna->hitung_pengguna();
        $data['total_applicants'] = $this->model_pengguna->hitung_pelamar();
        $data['total_assessments'] = $this->model_penilaian->hitung_penilaian();
        $data['completed_assessments'] = $this->model_penilaian->hitung_penilaian_selesai_semua();

        // Get recent applications
        $data['recent_applications'] = $this->model_lamaran->dapatkan_lamaran_terbaru(5);

        // Get job categories with count for chart
        $data['job_categories'] = $this->model_kategori->dapatkan_kategori_lowongan_dengan_jumlah();

        // Get monthly application statistics for current year
        $current_year = date('Y');
        $monthly_stats = $this->model_lamaran->dapatkan_statistik_lamaran_bulanan($current_year);

        // Initialize array with 0 for all months
        $monthly_data = array_fill(1, 12, 0);

        // Fill in actual data
        foreach ($monthly_stats as $stat) {
            $monthly_data[$stat->month] = $stat->count;
        }

        $data['monthly_application_stats'] = $monthly_data;

        // Get application status statistics
        $data['application_status_stats'] = [
            'pending' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('pending'),
            'reviewed' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('reviewed'),
            'shortlisted' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('shortlisted'),
            'interviewed' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('interviewed'),
            'offered' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('offered'),
            'hired' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('hired'),
            'rejected' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('rejected')
        ];

        // Get applications per job position
        $data['applications_per_job'] = $this->model_lamaran->dapatkan_jumlah_lamaran_per_lowongan();

        // Load views
        $data['title'] = 'Dasbor Admin';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates/admin_footer');
    }

    // Manajemen Lowongan
    public function lowongan() {
        // Get all jobs
        $data['jobs'] = $this->model_lowongan->dapatkan_lowongan_semua();

        // Load views
        $data['title'] = 'Manajemen Lowongan';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/jobs/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function tambah_lowongan() {
        // Get job categories
        $data['categories'] = $this->model_kategori->dapatkan_kategori_lowongan();

        // Form validation rules
        $this->form_validation->set_rules('title', 'Judul', 'trim|required');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required');
        $this->form_validation->set_rules('requirements', 'Persyaratan', 'trim|required');
        $this->form_validation->set_rules('responsibilities', 'Tanggung Jawab', 'trim|required');
        $this->form_validation->set_rules('location', 'Lokasi', 'trim|required');
        $this->form_validation->set_rules('job_type', 'Tipe Pekerjaan', 'trim|required');
        $this->form_validation->set_rules('deadline', 'Batas Waktu', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Tambah Lowongan Baru';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/jobs/add', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $job_data = array(
                'judul' => $this->input->post('title'),
                'id_kategori' => $this->input->post('category_id'),
                'deskripsi' => $this->input->post('description'),
                'persyaratan' => $this->input->post('requirements'),
                'tanggung_jawab' => $this->input->post('responsibilities'),
                'lokasi' => $this->input->post('location'),
                'jenis_pekerjaan' => $this->input->post('job_type'),
                'rentang_gaji' => $this->input->post('salary_range'),
                'batas_waktu' => $this->input->post('deadline'),
                'jumlah_lowongan' => $this->input->post('vacancies'),
                'unggulan' => $this->input->post('featured') ? 1 : 0,
                'status' => $this->input->post('status'),
                'dibuat_oleh' => $this->session->userdata('user_id')
            );

            // Insert job data
            $job_id = $this->model_lowongan->tambah_lowongan($job_data);

            if ($job_id) {
                // Show success message
                $this->session->set_flashdata('success', 'Lowongan berhasil ditambahkan.');

                // Check if user wants to manage document requirements
                if ($this->input->post('manage_documents') == '1') {
                    redirect('admin/dokumen_lowongan/' . $job_id);
                } else {
                    redirect('admin/lowongan');
                }
            } else {
                // If insertion fails, show error message
                $this->session->set_flashdata('error', 'Gagal menambahkan lowongan. Silakan coba lagi.');
                redirect('admin/tambah_lowongan');
            }
        }
    }

    // Fungsi tambahLowongan untuk menggantikan add_job
    public function tambahLowongan() {
        // Get job categories
        $data['categories'] = $this->model_kategori->dapatkan_kategori_lowongan();

        // Form validation rules
        $this->form_validation->set_rules('title', 'Judul', 'trim|required');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required');
        $this->form_validation->set_rules('requirements', 'Persyaratan', 'trim|required');
        $this->form_validation->set_rules('responsibilities', 'Tanggung Jawab', 'trim|required');
        $this->form_validation->set_rules('location', 'Lokasi', 'trim|required');
        $this->form_validation->set_rules('job_type', 'Tipe Pekerjaan', 'trim|required');
        $this->form_validation->set_rules('deadline', 'Batas Waktu', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Tambah Lowongan Baru';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/jobs/add', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $job_data = array(
                'judul' => $this->input->post('title'),
                'id_kategori' => $this->input->post('category_id'),
                'deskripsi' => $this->input->post('description'),
                'persyaratan' => $this->input->post('requirements'),
                'tanggung_jawab' => $this->input->post('responsibilities'),
                'lokasi' => $this->input->post('location'),
                'jenis_pekerjaan' => $this->input->post('job_type'),
                'rentang_gaji' => $this->input->post('salary_range'),
                'batas_waktu' => $this->input->post('deadline'),
                'jumlah_lowongan' => $this->input->post('vacancies'),
                'unggulan' => $this->input->post('featured') ? 1 : 0,
                'status' => $this->input->post('status'),
                'dibuat_oleh' => $this->session->userdata('user_id')
            );

            // Insert job data
            $job_id = $this->model_lowongan->tambah_lowongan($job_data);

            if ($job_id) {
                // Show success message
                $this->session->set_flashdata('success', 'Lowongan berhasil ditambahkan.');

                // Check if user wants to manage document requirements
                if ($this->input->post('manage_documents') == '1') {
                    redirect('admin/dokumen_lowongan/' . $job_id);
                } else {
                    redirect('admin/lowongan');
                }
            } else {
                // If insertion fails, show error message
                $this->session->set_flashdata('error', 'Gagal menambahkan lowongan. Silakan coba lagi.');
                redirect('admin/tambah_lowongan');
            }
        }
    }

    public function edit_lowongan($id) {
        // Get job details
        $data['job'] = $this->model_lowongan->dapatkan_lowongan($id);

        // If job not found, show 404
        if (!$data['job']) {
            show_404();
        }

        // Get job categories
        $data['categories'] = $this->model_kategori->dapatkan_kategori_lowongan();

        // Form validation rules
        $this->form_validation->set_rules('title', 'Judul', 'trim|required');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required');
        $this->form_validation->set_rules('requirements', 'Persyaratan', 'trim|required');
        $this->form_validation->set_rules('responsibilities', 'Tanggung Jawab', 'trim|required');
        $this->form_validation->set_rules('location', 'Lokasi', 'trim|required');
        $this->form_validation->set_rules('job_type', 'Tipe Pekerjaan', 'trim|required');
        $this->form_validation->set_rules('deadline', 'Batas Waktu', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Edit Lowongan';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/jobs/edit', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $job_data = array(
                'judul' => $this->input->post('title'),
                'id_kategori' => $this->input->post('category_id'),
                'deskripsi' => $this->input->post('description'),
                'persyaratan' => $this->input->post('requirements'),
                'tanggung_jawab' => $this->input->post('responsibilities'),
                'lokasi' => $this->input->post('location'),
                'jenis_pekerjaan' => $this->input->post('job_type'),
                'rentang_gaji' => $this->input->post('salary_range'),
                'batas_waktu' => $this->input->post('deadline'),
                'jumlah_lowongan' => $this->input->post('vacancies'),
                'unggulan' => $this->input->post('featured') ? 1 : 0,
                'status' => $this->input->post('status')
            );

            // Update job data
            $result = $this->model_lowongan->perbarui_lowongan($id, $job_data);

            if ($result) {
                // Show success message
                $this->session->set_flashdata('success', 'Lowongan berhasil diperbarui.');
                redirect('admin/lowongan');
            } else {
                // If update fails, show error message
                $this->session->set_flashdata('error', 'Gagal memperbarui lowongan. Silakan coba lagi.');
                redirect('admin/edit_lowongan/' . $id);
            }
        }
    }

    public function hapus_lowongan($id) {
        // Delete job
        $result = $this->model_lowongan->hapus_lowongan($id);

        if ($result) {
            // Show success message
            $this->session->set_flashdata('success', 'Lowongan berhasil dihapus.');
        } else {
            // If deletion fails, show error message
            $this->session->set_flashdata('error', 'Gagal menghapus lowongan. Silakan coba lagi.');
        }

        redirect('admin/lowongan');
    }

    // Manajemen Pelamar
    public function lamaran() {
        // Get all applications
        $data['applications'] = $this->model_lamaran->dapatkan_lamaran_semua();

        // Get application status statistics for all possible statuses
        $data['application_status_stats'] = [
            // Status dasar dalam bahasa Inggris
            'pending' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('pending'),
            'reviewed' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('reviewed'),
            'shortlisted' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('shortlisted'),
            'interviewed' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('interviewed'),
            'offered' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('offered'),
            'hired' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('hired'),
            'rejected' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('rejected'),

            // Status dalam bahasa Indonesia
            'interview' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('interview'),
            'diterima' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('diterima'),
            'ditolak' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('ditolak'),
            'seleksi' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('seleksi'),
            'wawancara' => $this->model_lamaran->hitung_lamaran_berdasarkan_status('wawancara')
        ];

        // Get monthly application statistics for current year
        $current_year = date('Y');
        $monthly_stats = $this->model_lamaran->dapatkan_statistik_lamaran_bulanan($current_year);

        // Initialize array with 0 for all months
        $monthly_data = array_fill(1, 12, 0);

        // Fill in actual data
        foreach ($monthly_stats as $stat) {
            $monthly_data[$stat->month] = $stat->count;
        }

        $data['monthly_application_stats'] = $monthly_data;

        // Load views
        $data['title'] = 'Manajemen Lamaran';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/lamaran/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function detail_lamaran($id) {
        // Get application details
        $data['application'] = $this->model_lamaran->dapatkan_lamaran($id);

        // If application not found, show 404
        if (!$data['application']) {
            show_404();
        }

        // Get applicant profile
        $data['profile'] = $this->model_pelamar->dapatkan_profil($data['application']->id_pelamar);

        // Get applicant data (for the view)
        $data['applicant'] = $this->model_pengguna->dapatkan_pengguna($data['application']->id_pelamar);

        // Get job details
        $data['job'] = $this->model_lowongan->dapatkan_lowongan($data['application']->id_pekerjaan);

        // Get assessment results
        $data['assessments'] = $this->model_penilaian->dapatkan_penilaian_pelamar($id);

        // Get uploaded documents
        $data['documents'] = $this->model_dokumen->dapatkan_dokumen_lamaran($id);

        // Load views
        $data['title'] = 'Detail Lamaran';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/lamaran/detail', $data);
        $this->load->view('templates/admin_footer');
    }

    public function perbarui_status_lamaran($id, $status) {
        // Load fonnte helper
        $this->load->helper('fonnte');

        // Get notify parameter
        $notify = $this->input->get('notify') === '1';

        // Get catatan parameter
        $catatan = $this->input->get('catatan');

        // Get application details
        $application = $this->model_lamaran->dapatkan_lamaran($id);

        // If application not found, show 404
        if (!$application) {
            show_404();
        }

        // Get job details
        $job = $this->model_lowongan->dapatkan_lowongan($application->id_pekerjaan);

        // Get applicant details
        $applicant = $this->model_pengguna->dapatkan_pengguna($application->id_pelamar);

        // Update application status
        $data = [
            'status' => $status,
            'diperbarui_pada' => date('Y-m-d H:i:s')
        ];

        // Add catatan if provided
        if (!empty($catatan)) {
            $data['catatan_admin'] = $catatan;
        }

        $result = $this->model_lamaran->perbarui_lamaran($id, $data);

        if ($result) {
            // Send WhatsApp notification if requested
            if ($notify && $applicant && $applicant->telepon) {
                $whatsapp_result = $this->kirim_notifikasi_whatsapp($applicant, $job, $status, $catatan);

                if ($whatsapp_result && isset($whatsapp_result['success']) && $whatsapp_result['success']) {
                    $this->session->set_flashdata('success', 'Status lamaran berhasil diperbarui menjadi ' . ucfirst($status) . ' dan notifikasi WhatsApp telah dikirim.');
                } else {
                    $this->session->set_flashdata('success', 'Status lamaran berhasil diperbarui menjadi ' . ucfirst($status) . ', tetapi gagal mengirim notifikasi WhatsApp.');
                    if ($whatsapp_result && isset($whatsapp_result['error'])) {
                        $this->session->set_flashdata('error', 'Error WhatsApp: ' . $whatsapp_result['error']);
                    }
                }
            } else {
                if (!$applicant->telepon) {
                    $this->session->set_flashdata('success', 'Status lamaran berhasil diperbarui menjadi ' . ucfirst($status) . '. Notifikasi WhatsApp tidak dikirim karena nomor telepon pelamar tidak tersedia.');
                } else {
                    $this->session->set_flashdata('success', 'Status lamaran berhasil diperbarui menjadi ' . ucfirst($status) . '.');
                }
            }
        } else {
            // If update fails, show error message
            $this->session->set_flashdata('error', 'Gagal memperbarui status lamaran. Silakan coba lagi.');
        }

        redirect('admin/detail_lamaran/' . $id);
    }

    // Kirim notifikasi WhatsApp
    private function kirim_notifikasi_whatsapp($applicant, $job, $status, $catatan = '') {
        // Get message based on status with complete information
        $message = dapatkan_pesan_status_lamaran($status, $job->judul, $applicant->nama_lengkap);

        // Add catatan if provided
        if (!empty($catatan)) {
            $message .= "\n\n*Catatan dari HR:*\n" . $catatan;
        }

        // Send WhatsApp notification
        $whatsapp_result = kirim_whatsapp($applicant->telepon, $message);

        return $whatsapp_result;
    }

    // Update status pelamar via URL
    public function updateStatusPelamar($id, $status) {
        // Update application status
        $result = $this->model_lamaran->perbarui_status($id, $status);

        if ($result) {
            // Show success message
            $this->session->set_flashdata('success', 'Status lamaran berhasil diperbarui menjadi ' . ucfirst($status) . '.');
        } else {
            // If update fails, show error message
            $this->session->set_flashdata('error', 'Gagal memperbarui status lamaran. Silakan coba lagi.');
        }

        redirect('admin/lamaran');
    }

    // Alias untuk updateStatusPelamar dengan nama Indonesia
    public function perbaruiStatusPelamar($id, $status) {
        return $this->updateStatusPelamar($id, $status);
    }

    // Perbarui status lamaran dengan notifikasi
    public function update_application_status($id, $status) {
        // Load fonnte helper
        $this->load->helper('fonnte');

        // Get notify parameter
        $notify = $this->input->get('notify') === '1';

        // Get application details
        $application = $this->model_lamaran->dapatkan_lamaran($id);

        // If application not found, show 404
        if (!$application) {
            show_404();
        }

        // Get job details
        $job = $this->model_lowongan->dapatkan_lowongan($application->id_pekerjaan);

        // Get applicant details
        $applicant = $this->model_pengguna->dapatkan_pengguna($application->id_pelamar);

        // Update application status
        $data = [
            'status' => $status,
            'diperbarui_pada' => date('Y-m-d H:i:s')
        ];

        $result = $this->model_lamaran->perbarui_lamaran($id, $data);

        if ($result) {
            // If notification is requested and applicant has a phone number
            if ($notify && !empty($applicant->phone)) {
                // Get message based on status with complete information
                $message = dapatkan_pesan_status_lamaran($status, $job->title, $applicant->full_name);

                // Send WhatsApp notification
                $whatsapp_result = kirim_whatsapp($applicant->phone, $message);

                if ($whatsapp_result['success']) {
                    $this->session->set_flashdata('success', 'Status lamaran berhasil diperbarui menjadi ' . ucfirst($status) . ' dan notifikasi WhatsApp telah dikirim.');
                } else {
                    $this->session->set_flashdata('success', 'Status lamaran berhasil diperbarui menjadi ' . ucfirst($status) . ', tetapi gagal mengirim notifikasi WhatsApp.');
                    $this->session->set_flashdata('error', 'Pesan error: ' . ($whatsapp_result['error'] ?? $whatsapp_result['message'] ?? 'Unknown error'));
                }
            } else {
                $this->session->set_flashdata('success', 'Status lamaran berhasil diperbarui menjadi ' . ucfirst($status) . '.');
            }
        } else {
            // If update fails, show error message
            $this->session->set_flashdata('error', 'Gagal memperbarui status lamaran. Silakan coba lagi.');
        }

        redirect('admin/detail_lamaran/' . $id);
    }

    // Add application note
    public function add_application_note($id) {
        // Get note from form
        $note = $this->input->post('note');

        // Update application with note
        $result = $this->model_lamaran->tambah_catatan_admin($id, $note);

        if ($result) {
            // Show success message
            $this->session->set_flashdata('success', 'Catatan berhasil ditambahkan.');
        } else {
            // If update fails, show error message
            $this->session->set_flashdata('error', 'Gagal menambahkan catatan. Silakan coba lagi.');
        }

        redirect('admin/detail_lamaran/' . $id);
    }

    // Alias untuk add_application_note dengan nama Indonesia
    public function tambah_catatan_lamaran($id) {
        return $this->add_application_note($id);
    }

    // Edit pelamar
    public function editPelamar($id) {
        // Get application details
        $data['application'] = $this->model_lamaran->dapatkan_lamaran($id);

        // If application not found, show 404
        if (!$data['application']) {
            show_404();
        }

        // Get job list for dropdown
        $data['jobs'] = $this->model_lowongan->dapatkan_lowongan_aktif_semua();

        // Get applicant profile
        $data['profile'] = $this->model_pelamar->dapatkan_profil($data['application']->id_pelamar);

        // Form validation rules
        $this->form_validation->set_rules('job_id', 'Lowongan', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Load views
            $data['title'] = 'Edit Lamaran';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/lamaran/edit', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $application_data = array(
                'job_id' => $this->input->post('job_id'),
                'status' => $this->input->post('status'),
                'diperbarui_pada' => date('Y-m-d H:i:s')
            );

            // Update application data
            $result = $this->model_lamaran->perbarui_lamaran($id, $application_data);

            if ($result) {
                // Show success message
                $this->session->set_flashdata('success', 'Lamaran berhasil diperbarui.');
                redirect('admin/lamaran');
            } else {
                // If update fails, show error message
                $this->session->set_flashdata('error', 'Gagal memperbarui lamaran. Silakan coba lagi.');
                redirect('admin/editPelamar/' . $id);
            }
        }
    }

    // Delete pelamar
    public function deletePelamar($id) {
        // Delete application
        $result = $this->model_lamaran->hapus_lamaran($id);

        if ($result) {
            // Show success message
            $this->session->set_flashdata('success', 'Lamaran berhasil dihapus.');
        } else {
            // If deletion fails, show error message
            $this->session->set_flashdata('error', 'Gagal menghapus lamaran. Silakan coba lagi.');
        }

        redirect('admin/lamaran');
    }

    // Alias untuk deletePelamar dengan nama Indonesia
    public function hapusPelamar($id) {
        return $this->deletePelamar($id);
    }

    // Download CV
    public function downloadCV($id) {
        // Get application details
        $application = $this->model_lamaran->dapatkan_lamaran($id);

        // If application not found, show 404
        if (!$application) {
            show_404();
        }

        $cv_file = null;
        $file_path = null;

        // First, try to get CV from application
        if ($application->cv) {
            $cv_file = $application->cv;
            $file_path = './uploads/cv/' . $cv_file;
        } else {
            // If no CV in application, try to get from profile
            $profile = $this->model_pelamar->dapatkan_profil($application->id_pelamar);
            if ($profile && $profile->cv) {
                $cv_file = $profile->cv;
                $file_path = './uploads/cv/' . $cv_file;
            }
        }

        // If no CV found, show 404
        if (!$cv_file || !$file_path) {
            $this->session->set_flashdata('error', 'CV tidak ditemukan untuk lamaran ini.');
            redirect('admin/detail_lamaran/' . $id);
        }

        // Check if file exists
        if (!file_exists($file_path)) {
            $this->session->set_flashdata('error', 'File CV tidak ditemukan di server.');
            redirect('admin/detail_lamaran/' . $id);
        }

        // Get file info
        $file_info = pathinfo($file_path);
        $file_name = $application->applicant_name . '_CV.' . $file_info['extension'];

        // Force download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    }

    // Legacy support - alias untuk downloadCV
    public function downloadResume($id) {
        return $this->downloadCV($id);
    }

    // Alias untuk downloadCV dengan nama Indonesia
    public function unduhCV($id) {
        return $this->downloadCV($id);
    }

    // Legacy support - alias untuk unduhCV
    public function unduhResume($id) {
        return $this->downloadCV($id);
    }

    // Legacy support - alias untuk unduhCV
    public function unduh_resume($id) {
        return $this->downloadCV($id);
    }

    // ===== MANAJEMEN DOKUMEN LOWONGAN =====

    // Kelola dokumen lowongan
    public function dokumen_lowongan($job_id) {
        // Get job details
        $data['job'] = $this->model_lowongan->dapatkan_lowongan($job_id);

        // If job not found, show 404
        if (!$data['job']) {
            show_404();
        }

        // Get document requirements for this job
        $data['documents'] = $this->model_dokumen->dapatkan_dokumen_lowongan($job_id);

        // Load views
        $data['title'] = 'Kelola Dokumen Lowongan';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/jobs/documents', $data);
        $this->load->view('templates/admin_footer');
    }

    // Tambah dokumen lowongan
    public function tambah_dokumen_lowongan($job_id) {
        // Get job details
        $data['job'] = $this->model_lowongan->dapatkan_lowongan($job_id);

        // If job not found, show 404
        if (!$data['job']) {
            show_404();
        }

        // Form validation rules
        $this->form_validation->set_rules('jenis_dokumen', 'Jenis Dokumen', 'trim|required');
        $this->form_validation->set_rules('nama_dokumen', 'Nama Dokumen', 'trim|required');
        $this->form_validation->set_rules('wajib', 'Wajib', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Tambah Dokumen Lowongan';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/jobs/add_document', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $document_data = array(
                'id_lowongan' => $job_id,
                'jenis_dokumen' => $this->input->post('jenis_dokumen'),
                'nama_dokumen' => $this->input->post('nama_dokumen'),
                'wajib' => $this->input->post('wajib'),
                'format_diizinkan' => $this->input->post('format_diizinkan'),
                'ukuran_maksimal' => $this->input->post('ukuran_maksimal'),
                'deskripsi' => $this->input->post('deskripsi')
            );

            // Insert document data
            $document_id = $this->model_dokumen->tambah_dokumen_lowongan($document_data);

            if ($document_id) {
                // Show success message
                $this->session->set_flashdata('success', 'Dokumen lowongan berhasil ditambahkan.');
                redirect('admin/dokumen_lowongan/' . $job_id);
            } else {
                // If insertion fails, show error message
                $this->session->set_flashdata('error', 'Gagal menambahkan dokumen lowongan. Silakan coba lagi.');
                redirect('admin/tambah_dokumen_lowongan/' . $job_id);
            }
        }
    }

    // Edit dokumen lowongan
    public function edit_dokumen_lowongan($id) {
        // Get document details
        $data['document'] = $this->model_dokumen->dapatkan_dokumen_lowongan_by_id($id);

        // If document not found, show 404
        if (!$data['document']) {
            show_404();
        }

        // Get job details
        $data['job'] = $this->model_lowongan->dapatkan_lowongan($data['document']->id_lowongan);

        // Form validation rules
        $this->form_validation->set_rules('jenis_dokumen', 'Jenis Dokumen', 'trim|required');
        $this->form_validation->set_rules('nama_dokumen', 'Nama Dokumen', 'trim|required');
        $this->form_validation->set_rules('wajib', 'Wajib', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Edit Dokumen Lowongan';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/jobs/edit_document', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $document_data = array(
                'jenis_dokumen' => $this->input->post('jenis_dokumen'),
                'nama_dokumen' => $this->input->post('nama_dokumen'),
                'wajib' => $this->input->post('wajib'),
                'format_diizinkan' => $this->input->post('format_diizinkan'),
                'ukuran_maksimal' => $this->input->post('ukuran_maksimal'),
                'deskripsi' => $this->input->post('deskripsi')
            );

            // Update document data
            $result = $this->model_dokumen->perbarui_dokumen_lowongan($id, $document_data);

            if ($result) {
                // Show success message
                $this->session->set_flashdata('success', 'Dokumen lowongan berhasil diperbarui.');
                redirect('admin/dokumen_lowongan/' . $data['document']->id_lowongan);
            } else {
                // If update fails, show error message
                $this->session->set_flashdata('error', 'Gagal memperbarui dokumen lowongan. Silakan coba lagi.');
                redirect('admin/edit_dokumen_lowongan/' . $id);
            }
        }
    }

    // Hapus dokumen lowongan
    public function hapus_dokumen_lowongan($id) {
        // Get document details
        $document = $this->model_dokumen->dapatkan_dokumen_lowongan_by_id($id);

        // If document not found, show 404
        if (!$document) {
            show_404();
        }

        // Delete document
        $result = $this->model_dokumen->hapus_dokumen_lowongan($id);

        if ($result) {
            // Show success message
            $this->session->set_flashdata('success', 'Dokumen lowongan berhasil dihapus.');
        } else {
            // If deletion fails, show error message
            $this->session->set_flashdata('error', 'Gagal menghapus dokumen lowongan. Silakan coba lagi.');
        }

        redirect('admin/dokumen_lowongan/' . $document->id_lowongan);
    }

    // Atur dokumen default untuk lowongan
    public function atur_dokumen_default($job_id) {
        // Get job details
        $job = $this->model_lowongan->dapatkan_lowongan($job_id);

        // If job not found, show 404
        if (!$job) {
            show_404();
        }

        // Check if job already has document requirements
        if ($this->model_dokumen->cek_dokumen_lowongan_exists($job_id)) {
            $this->session->set_flashdata('error', 'Lowongan ini sudah memiliki persyaratan dokumen. Hapus semua dokumen terlebih dahulu untuk mengatur ulang.');
            redirect('admin/dokumen_lowongan/' . $job_id);
        }

        // Get default document requirements
        $default_documents = $this->model_dokumen->dapatkan_dokumen_default();

        // Add default document requirements for this job
        $success = true;
        foreach ($default_documents as $document) {
            $document['id_lowongan'] = $job_id;
            $result = $this->model_dokumen->tambah_dokumen_lowongan($document);
            if (!$result) {
                $success = false;
            }
        }

        if ($success) {
            // Show success message
            $this->session->set_flashdata('success', 'Dokumen default berhasil ditambahkan ke lowongan.');
        } else {
            // If insertion fails, show error message
            $this->session->set_flashdata('error', 'Gagal menambahkan beberapa dokumen default. Silakan coba lagi.');
        }

        redirect('admin/dokumen_lowongan/' . $job_id);
    }

    // Hapus semua dokumen lowongan
    public function hapus_semua_dokumen_lowongan($job_id) {
        // Get job details
        $job = $this->model_lowongan->dapatkan_lowongan($job_id);

        // If job not found, show 404
        if (!$job) {
            show_404();
        }

        // Delete all document requirements for this job
        $result = $this->model_dokumen->hapus_semua_dokumen_lowongan($job_id);

        if ($result) {
            // Show success message
            $this->session->set_flashdata('success', 'Semua dokumen lowongan berhasil dihapus.');
        } else {
            // If deletion fails, show error message
            $this->session->set_flashdata('error', 'Gagal menghapus dokumen lowongan. Silakan coba lagi.');
        }

        redirect('admin/dokumen_lowongan/' . $job_id);
    }

    // Download dokumen lamaran
    public function download_dokumen_lamaran($id) {
        // Get document details
        $document = $this->model_dokumen->dapatkan_dokumen_lamaran_by_id($id);

        // If document not found, show 404
        if (!$document) {
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
            redirect('admin/detail_lamaran/' . $document->id_lamaran);
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

    // Alias untuk download_dokumen_lamaran dengan nama Indonesia
    public function unduh_dokumen_lamaran($id) {
        return $this->download_dokumen_lamaran($id);
    }

    // Print pelamar
    public function printPelamar($id) {
        // Get application details
        $data['application'] = $this->model_lamaran->dapatkan_lamaran($id);

        // If application not found, show 404
        if (!$data['application']) {
            show_404();
        }

        // Get applicant profile
        $data['profile'] = $this->model_pelamar->dapatkan_profil($data['application']->id_pelamar);

        // Get job details
        $data['job'] = $this->model_lowongan->dapatkan_lowongan($data['application']->id_pekerjaan);

        // Load print view
        $data['title'] = 'Cetak Lamaran';
        $this->load->view('admin/lamaran/print', $data);
    }

    // Alias untuk printPelamar dengan nama Indonesia
    public function cetakPelamar($id) {
        return $this->printPelamar($id);
    }

    // Lihat Lamaran Berdasarkan Lowongan
    public function lamaran_lowongan($job_id) {
        // Get job details
        $data['job'] = $this->model_lowongan->dapatkan_lowongan($job_id);

        // If job not found, show 404
        if (!$data['job']) {
            show_404();
        }

        // Get applications for this job
        $data['applications'] = $this->model_lamaran->dapatkan_lamaran_lowongan($job_id);

        // Load views
        $data['title'] = 'Lamaran untuk ' . $data['job']->title;
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/lamaran/lamaran_pekerjaan', $data);
        $this->load->view('templates/admin_footer');
    }

    // Manajemen Penilaian
    public function penilaian() {
        // Get all assessments
        $assessments = $this->model_penilaian->dapatkan_semua_penilaian();

        // Add question count for each assessment
        foreach ($assessments as &$assessment) {
            $assessment->question_count = $this->model_penilaian->hitung_soal_penilaian($assessment->id);
        }
        $data['assessments'] = $assessments;

        // Get assessment types for filter
        $data['assessment_types'] = $this->model_penilaian->dapatkan_jenis_penilaian();

        // Get jobs for filter
        $data['jobs'] = $this->model_lowongan->dapatkan_lowongan_aktif(100, 0);

        // Get chart data for assessment types
        $data['chart_data'] = $this->dapatkan_data_chart_penilaian();

        // Load views
        $data['title'] = 'Manajemen Penilaian';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/penilaian/index', $data);
        $this->load->view('templates/admin_footer');
    }

    // Tambah Penilaian
    public function tambah_penilaian() {
        // Get assessment types
        $data['assessment_types'] = $this->model_penilaian->dapatkan_jenis_penilaian();

        // Get jobs for dropdown
        $data['jobs'] = $this->model_lowongan->dapatkan_lowongan_aktif(100, 0);

        // Form validation rules
        $this->form_validation->set_rules('title', 'Judul', 'trim|required');
        $this->form_validation->set_rules('assessment_type_id', 'Jenis Penilaian', 'trim|required');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required');
        $this->form_validation->set_rules('time_limit', 'Batas Waktu', 'trim|numeric');
        $this->form_validation->set_rules('passing_score', 'Nilai Kelulusan', 'trim|numeric');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Tambah Penilaian Baru';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/penilaian/tambah', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $assessment_data = array(
                'judul' => $this->input->post('title'),
                'id_jenis' => $this->input->post('assessment_type_id'),
                'deskripsi' => $this->input->post('description'),
                'petunjuk' => $this->input->post('instructions'),
                'batas_waktu' => $this->input->post('time_limit'),
                'nilai_lulus' => $this->input->post('passing_score'),
                'maksimal_percobaan' => $this->input->post('max_attempts'),
                'aktif' => $this->input->post('is_active') ? 1 : 0,
                'acak_soal' => $this->input->post('acak_soal') ? 1 : 0,
                'mode_cat' => $this->input->post('mode_cat') ? 1 : 0,
                'dibuat_oleh' => $this->session->userdata('user_id'),
                'dibuat_pada' => date('Y-m-d H:i:s')
            );

            // Insert assessment data
            $assessment_id = $this->model_penilaian->tambah_penilaian($assessment_data);

            if ($assessment_id) {
                // If job_id is provided, assign assessment to job
                $job_id = $this->input->post('job_id');
                if (!empty($job_id)) {
                    $this->model_penilaian->tetapkan_penilaian_ke_lowongan($job_id, $assessment_id);
                }

                // Show success message
                $this->session->set_flashdata('success', 'Penilaian berhasil ditambahkan.');
                redirect('admin/soal_penilaian/' . $assessment_id);
            } else {
                // If insertion fails, show error message
                $this->session->set_flashdata('error', 'Gagal menambahkan penilaian. Silakan coba lagi.');
                redirect('admin/tambah_penilaian');
            }
        }
    }

    // Edit Penilaian
    public function edit_penilaian($id) {
        // Get assessment details
        $data['assessment'] = $this->model_penilaian->dapatkan_penilaian($id);

        // If assessment not found, show 404
        if (!$data['assessment']) {
            show_404();
        }

        // Get assessment types
        $data['assessment_types'] = $this->model_penilaian->dapatkan_jenis_penilaian();

        // Get jobs for dropdown
        $data['jobs'] = $this->model_lowongan->dapatkan_lowongan_aktif(100, 0);

        // Get assigned job (if any)
        $data['assigned_job'] = $this->model_penilaian->dapatkan_lowongan_penilaian($id);

        // Form validation rules
        $this->form_validation->set_rules('title', 'Judul', 'trim|required');
        $this->form_validation->set_rules('assessment_type_id', 'Jenis Penilaian', 'trim|required');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required');
        $this->form_validation->set_rules('time_limit', 'Batas Waktu', 'trim|numeric');
        $this->form_validation->set_rules('passing_score', 'Nilai Kelulusan', 'trim|numeric');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Edit Penilaian';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/penilaian/edit', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $assessment_data = array(
                'judul' => $this->input->post('title'),
                'id_jenis' => $this->input->post('assessment_type_id'),
                'deskripsi' => $this->input->post('description'),
                'petunjuk' => $this->input->post('instructions'),
                'batas_waktu' => $this->input->post('time_limit'),
                'nilai_lulus' => $this->input->post('passing_score'),
                'maksimal_percobaan' => $this->input->post('max_attempts'),
                'aktif' => $this->input->post('is_active') ? 1 : 0,
                'acak_soal' => $this->input->post('acak_soal') ? 1 : 0,
                'mode_cat' => $this->input->post('mode_cat') ? 1 : 0,
                'diperbarui_pada' => date('Y-m-d H:i:s')
            );

            // Update assessment data
            $result = $this->model_penilaian->perbarui_penilaian($id, $assessment_data);

            if ($result) {
                // Update job assignment if needed
                $job_id = $this->input->post('job_id');
                $current_job = $data['assigned_job'] ? $data['assigned_job']->job_id : null;

                // If job assignment has changed
                if ($job_id != $current_job) {
                    // Remove current assignment if exists
                    if ($current_job) {
                        $this->model_penilaian->hapus_penilaian_dari_lowongan($current_job, $id);
                    }

                    // Add new assignment if job_id is provided
                    if (!empty($job_id)) {
                        $this->model_penilaian->tetapkan_penilaian_ke_lowongan($job_id, $id);
                    }
                }

                // Show success message
                $this->session->set_flashdata('success', 'Penilaian berhasil diperbarui.');
                redirect('admin/penilaian');
            } else {
                // If update fails, show error message
                $this->session->set_flashdata('error', 'Gagal memperbarui penilaian. Silakan coba lagi.');
                redirect('admin/edit_penilaian/' . $id);
            }
        }
    }

    // Hapus Penilaian
    public function hapus_penilaian($id) {
        // Get assessment details
        $assessment = $this->model_penilaian->dapatkan_penilaian($id);

        // If assessment not found, show 404
        if (!$assessment) {
            show_404();
        }

        // Check if assessment has been used by applicants
        $has_applicants = $this->model_penilaian->cek_penilaian_digunakan($id);

        if ($has_applicants) {
            // If assessment has been used, show error message
            $this->session->set_flashdata('error', 'Penilaian tidak dapat dihapus karena sudah digunakan oleh pelamar.');
            redirect('admin/penilaian');
            return;
        }

        // Delete assessment
        $result = $this->model_penilaian->hapus_penilaian($id);

        if ($result) {
            // Show success message
            $this->session->set_flashdata('success', 'Penilaian berhasil dihapus.');
        } else {
            // If deletion fails, show error message
            $this->session->set_flashdata('error', 'Gagal menghapus penilaian. Silakan coba lagi.');
        }

        redirect('admin/penilaian');
    }

    // Migrasi Status Lamaran
    public function migrasi_status_lamaran() {
        // Jalankan migrasi status
        $result = $this->model_lamaran->migrasi_status_lamaran();

        if ($result) {
            // Show success message
            $this->session->set_flashdata('success', 'Status lamaran berhasil dimigrasi ke format baru.');
        } else {
            // If migration fails, show error message
            $this->session->set_flashdata('error', 'Gagal migrasi status lamaran. Silakan coba lagi.');
        }

        redirect('admin/lamaran');
    }

    // Atur Penilaian untuk Lowongan
    public function assign_assessment($job_id, $application_id = null) {
        // Get job details
        $data['job'] = $this->model_lowongan->dapatkan_lowongan($job_id);

        // If job not found, show 404
        if (!$data['job']) {
            show_404();
        }

        // Get all active assessments
        $data['assessments'] = $this->model_penilaian->dapatkan_penilaian_aktif();

        // Get assessments already assigned to this job
        $data['assigned_assessments'] = $this->model_penilaian->dapatkan_penilaian_lowongan($job_id);

        // If application_id is provided, get application details
        if ($application_id) {
            $data['application'] = $this->model_lamaran->dapatkan_lamaran($application_id);

            // If application not found, show 404
            if (!$data['application']) {
                show_404();
            }

            // Get assessments already assigned to this applicant
            $data['applicant_assessments'] = $this->model_penilaian->dapatkan_penilaian_pelamar($application_id);
        }

        // Form submission handling
        if ($this->input->post('submit')) {
            $assessment_ids = $this->input->post('assessment_ids');

            if ($application_id) {
                // Assign assessments to specific applicant
                if (!empty($assessment_ids)) {
                    foreach ($assessment_ids as $assessment_id) {
                        // Check if already assigned
                        if (!$this->model_penilaian->cek_penilaian_sudah_ditetapkan($application_id, $assessment_id)) {
                            $applicant_assessment_data = array(
                                'id_lamaran' => $application_id,
                                'id_penilaian' => $assessment_id,
                                'status' => 'belum_mulai',
                                'ditugaskan_pada' => date('Y-m-d H:i:s'),
                                'ditugaskan_oleh' => $this->session->userdata('user_id'),
                                'dibuat_pada' => date('Y-m-d H:i:s')
                            );
                            $this->model_penilaian->tambah_penilaian_pelamar($applicant_assessment_data);
                        }
                    }
                    $this->session->set_flashdata('success', 'Penilaian berhasil ditetapkan kepada pelamar.');
                    redirect('admin/detail_lamaran/' . $application_id);
                } else {
                    $this->session->set_flashdata('error', 'Silakan pilih minimal satu penilaian.');
                }
            } else {
                // Assign assessments to job
                // First, remove all current assignments
                $this->model_penilaian->hapus_semua_penilaian_lowongan($job_id);

                // Then add new assignments
                if (!empty($assessment_ids)) {
                    foreach ($assessment_ids as $assessment_id) {
                        $this->model_penilaian->tetapkan_penilaian_ke_lowongan($job_id, $assessment_id);
                    }
                    $this->session->set_flashdata('success', 'Penilaian berhasil ditetapkan untuk lowongan ini.');
                    redirect('admin/lowongan');
                } else {
                    $this->session->set_flashdata('error', 'Silakan pilih minimal satu penilaian.');
                }
            }
        }

        // Load views
        $data['title'] = 'Atur Penilaian';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/penilaian/atur', $data);
        $this->load->view('templates/admin_footer');
    }

    // Alias untuk assign_assessment dengan nama Indonesia
    public function atur_penilaian($job_id, $application_id = null) {
        return $this->assign_assessment($job_id, $application_id);
    }

    // Kelola Soal Penilaian
    public function soal_penilaian($assessment_id) {
        // Get assessment details
        $data['assessment'] = $this->model_penilaian->dapatkan_penilaian($assessment_id);

        // If assessment not found, show 404
        if (!$data['assessment']) {
            show_404();
        }

        // Get assessment questions
        $data['questions'] = $this->model_penilaian->dapatkan_soal_penilaian($assessment_id);

        // Add option count for each question
        foreach ($data['questions'] as &$question) {
            if ($question->jenis_soal == 'pilihan_ganda' || $question->jenis_soal == 'benar_salah') {
                $options = $this->model_penilaian->dapatkan_opsi_soal($question->id);
                $question->option_count = count($options);
            } else {
                $question->option_count = 0;
            }
        }

        // Load views
        $data['title'] = 'Kelola Soal Penilaian';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/penilaian/soal', $data);
        $this->load->view('templates/admin_footer');
    }

    // Hasil Penilaian
    public function hasilPenilaian($assessment_id) {
        // Get assessment details
        $data['assessment'] = $this->model_penilaian->dapatkan_penilaian($assessment_id);

        // If assessment not found, show 404
        if (!$data['assessment']) {
            show_404();
        }

        // Get applicants who have taken this assessment
        $data['results'] = $this->model_penilaian->dapatkan_hasil_penilaian($assessment_id);

        // Get statistics
        $data['stats'] = [
            'total_applicants' => $this->model_penilaian->hitung_pelamar_penilaian($assessment_id),
            'completed' => $this->model_penilaian->hitung_penyelesaian_penilaian($assessment_id),
            'avg_score' => $this->model_penilaian->dapatkan_rata_rata_skor($assessment_id)
        ];

        // Load views
        $data['title'] = 'Hasil Penilaian';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/penilaian/hasil', $data);
        $this->load->view('templates/admin_footer');
    }

    // Detail Hasil Penilaian Pelamar
    public function detailHasilPenilaian($applicant_assessment_id) {
        // Get applicant assessment details
        $data['applicant_assessment'] = $this->model_penilaian->dapatkan_detail_penilaian_pelamar($applicant_assessment_id);

        // If applicant assessment not found, show 404
        if (!$data['applicant_assessment']) {
            show_404();
        }

        // Get assessment details
        $data['assessment'] = $this->model_penilaian->dapatkan_penilaian($data['applicant_assessment']->id_penilaian);

        // Get assessment questions with applicant's answers
        $data['questions_with_answers'] = $this->model_penilaian->dapatkan_soal_dengan_jawaban_pelamar($applicant_assessment_id);

        // Get applicant details
        $data['applicant'] = $this->model_pengguna->dapatkan_pengguna($data['applicant_assessment']->id_pelamar);

        // Load views
        $data['title'] = 'Detail Hasil Penilaian Pelamar';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/penilaian/detail_hasil', $data);
        $this->load->view('templates/admin_footer');
    }

    // Update nilai jawaban pelamar (untuk soal esai)
    public function update_nilai_jawaban() {
        // Set JSON response header
        header('Content-Type: application/json');

        // Get form data
        $answer_id = $this->input->post('answer_id');
        $nilai = $this->input->post('nilai');

        // Validate input
        if (!$answer_id || $nilai === null || $nilai === '') {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
            return;
        }

        // Validate numeric input
        if (!is_numeric($nilai) || $nilai < 0) {
            echo json_encode(['status' => 'error', 'message' => 'Nilai harus berupa angka positif']);
            return;
        }

        // Get current user ID
        $user_id = $this->session->userdata('user_id');

        // Update nilai jawaban
        $result = $this->model_penilaian->update_nilai_jawaban($answer_id, $nilai, $user_id);

        if ($result) {
            // Get the applicant assessment ID to recalculate score
            $this->db->select('id_penilaian_pelamar');
            $this->db->where('id', $answer_id);
            $answer_query = $this->db->get('jawaban_pelamar');
            $answer = $answer_query->row();

            if ($answer) {
                // Recalculate total score
                $this->model_penilaian->hitung_skor_penilaian_pelamar($answer->id_penilaian_pelamar);
            }

            echo json_encode(['status' => 'success', 'message' => 'Nilai berhasil disimpan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan nilai']);
        }
    }

    // Pratinjau Penilaian
    public function previewPenilaian($assessment_id) {
        // Get assessment details
        $data['assessment'] = $this->model_penilaian->dapatkan_penilaian($assessment_id);

        // If assessment not found, show 404
        if (!$data['assessment']) {
            show_404();
        }

        // Get assessment questions with options
        $data['questions'] = $this->model_penilaian->dapatkan_soal_penilaian($assessment_id);
        foreach ($data['questions'] as &$question) {
            if ($question->jenis_soal == 'pilihan_ganda' || $question->jenis_soal == 'benar_salah') {
                $question->options = $this->model_penilaian->dapatkan_opsi_soal($question->id);
            }
        }

        // Load views
        $data['title'] = 'Pratinjau Penilaian';
        $data['preview_mode'] = true;
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/penilaian/pratinjau', $data);
        $this->load->view('templates/admin_footer');
    }

    // Tetapkan Penilaian ke Pelamar
    public function tetapkanPenilaian($assessment_id) {
        // Get assessment details
        $data['assessment'] = $this->model_penilaian->dapatkan_penilaian($assessment_id);

        // If assessment not found, show 404
        if (!$data['assessment']) {
            show_404();
        }

        // Get job applications that can be assigned to this assessment
        // First, get the job associated with this assessment (if any)
        $job_id = $this->model_penilaian->dapatkan_lowongan_penilaian($assessment_id);

        if ($job_id && isset($job_id->job_id)) {
            // If assessment is associated with a job, get applications for that job
            $data['applications'] = $this->model_lamaran->dapatkan_lamaran_lowongan($job_id->job_id);
            $data['job'] = $this->model_lowongan->dapatkan_lowongan($job_id->job_id);
        } else {
            // If not associated with a job, get all active applications
            $data['applications'] = $this->model_lamaran->dapatkan_semua_lamaran_aktif();
            $data['job'] = null;
        }

        // Process form submission
        if ($this->input->post()) {
            $application_ids = $this->input->post('application_ids');

            if (!empty($application_ids)) {
                $success_count = 0;
                $already_assigned = 0;

                foreach ($application_ids as $application_id) {
                    // Check if already assigned
                    if (!$this->model_penilaian->cek_penilaian_sudah_ditetapkan($application_id, $assessment_id)) {
                        $applicant_assessment_data = array(
                            'id_lamaran' => $application_id,
                            'id_penilaian' => $assessment_id,
                            'status' => 'belum_mulai',
                            'ditugaskan_pada' => date('Y-m-d H:i:s'),
                            'ditugaskan_oleh' => $this->session->userdata('user_id'),
                            'dibuat_pada' => date('Y-m-d H:i:s')
                        );
                        $this->model_penilaian->tambah_penilaian_pelamar($applicant_assessment_data);
                        $success_count++;
                    } else {
                        $already_assigned++;
                    }
                }

                if ($success_count > 0) {
                    $this->session->set_flashdata('success', $success_count . ' pelamar berhasil ditetapkan untuk penilaian ini.');
                }

                if ($already_assigned > 0) {
                    $this->session->set_flashdata('info', $already_assigned . ' pelamar sudah ditetapkan sebelumnya.');
                }

                redirect('admin/hasil-penilaian/' . $assessment_id);
            } else {
                $this->session->set_flashdata('error', 'Silakan pilih minimal satu pelamar.');
            }
        }

        // Load views
        $data['title'] = 'Tetapkan Penilaian ke Pelamar';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/penilaian/tetapkan', $data);
        $this->load->view('templates/admin_footer');
    }

    // Tambah Soal
    public function tambah_soal($assessment_id) {
        // Get assessment details
        $data['assessment'] = $this->model_penilaian->dapatkan_penilaian($assessment_id);

        // If assessment not found, show 404
        if (!$data['assessment']) {
            show_404();
        }

        // Form validation rules
        $this->form_validation->set_rules('question_text', 'Teks Pertanyaan', 'trim|required');
        $this->form_validation->set_rules('question_type', 'Jenis Pertanyaan', 'trim|required');
        $this->form_validation->set_rules('points', 'Poin', 'trim|required|numeric');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Tambah Soal Baru';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/penilaian/tambah_soal', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $question_data = array(
                'id_penilaian' => $assessment_id,
                'teks_soal' => $this->input->post('question_text'),
                'jenis_soal' => $this->input->post('question_type'),
                'poin' => $this->input->post('points'),
                'dibuat_pada' => date('Y-m-d H:i:s')
            );

            // Handle image upload if provided
            if (!empty($_FILES['question_image']['name'])) {
                // Define upload path using absolute path
                $upload_dir = 'uploads/gambar_soal';
                $upload_path = str_replace('\\', '/', realpath(FCPATH . $upload_dir));

                // Log path information for debugging
                log_message('debug', 'FCPATH: ' . FCPATH);
                log_message('debug', 'Upload directory: ' . $upload_dir);
                log_message('debug', 'Full upload path: ' . $upload_path);
                log_message('debug', 'Directory exists: ' . (is_dir($upload_path) ? 'Yes' : 'No'));
                log_message('debug', 'Directory writable: ' . (is_writable($upload_path) ? 'Yes' : 'No'));

                // Create directory if it doesn't exist
                if (!is_dir($upload_path)) {
                    if (!mkdir($upload_path, 0777, true)) {
                        log_message('error', 'Failed to create directory: ' . $upload_path);
                        $this->session->set_flashdata('error', 'Gagal membuat direktori upload: ' . $upload_path);
                        redirect('admin/tambah_soal/' . $assessment_id);
                        return;
                    }
                }

                // Ensure directory is writable
                if (!is_writable($upload_path)) {
                    if (!chmod($upload_path, 0777)) {
                        log_message('error', 'Failed to set directory permissions: ' . $upload_path);
                        $this->session->set_flashdata('error', 'Direktori tidak dapat ditulis: ' . $upload_path);
                        redirect('admin/tambah_soal/' . $assessment_id);
                        return;
                    }
                }

                // Load upload library
                $this->load->library('upload');

                // Configure upload
                $config = array(
                    'upload_path' => $upload_path,
                    'allowed_types' => 'gif|jpg|jpeg|png',
                    'max_size' => 4096, // 4MB
                    'file_name' => 'question_' . time() . '_' . rand(1000, 9999),
                    'overwrite' => FALSE,
                    'remove_spaces' => TRUE,
                    'encrypt_name' => FALSE
                );

                // Initialize upload configuration
                $this->upload->initialize($config);

                // Attempt upload
                if ($this->upload->do_upload('question_image')) {
                    $upload_data = $this->upload->data();
                    $question_data['gambar_soal'] = $upload_data['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    log_message('error', 'Upload error: ' . $error);
                    log_message('error', 'Upload path used: ' . $config['upload_path']);
                    $this->session->set_flashdata('error', 'Gagal mengunggah gambar: ' . $error);
                    redirect('admin/tambah_soal/' . $assessment_id);
                    return;
                }
            }

            // Insert question data
            $question_id = $this->model_penilaian->tambah_soal($question_data);

            if ($question_id) {
                // If question type is multiple choice or true/false, add options
                if ($this->input->post('question_type') == 'pilihan_ganda' || $this->input->post('question_type') == 'benar_salah') {
                    redirect('admin/opsi_soal/' . $question_id);
                } else {
                    // Show success message
                    $this->session->set_flashdata('success', 'Soal berhasil ditambahkan.');
                    redirect('admin/soal_penilaian/' . $assessment_id);
                }
            } else {
                // If insertion fails, show error message
                $this->session->set_flashdata('error', 'Gagal menambahkan soal. Silakan coba lagi.');
                redirect('admin/tambah_soal/' . $assessment_id);
            }
        }
    }

    // Kelola Opsi Soal
    public function opsi_soal($question_id) {
        // Get question details
        $this->db->select('soal.*, penilaian.judul as assessment_title, penilaian.id as assessment_id');
        $this->db->from('soal');
        $this->db->join('penilaian', 'penilaian.id = soal.id_penilaian', 'left');
        $this->db->where('soal.id', $question_id);
        $query = $this->db->get();
        $data['question'] = $query->row();

        // If question not found, show 404
        if (!$data['question']) {
            show_404();
        }

        // Get question options
        $data['options'] = $this->model_penilaian->dapatkan_opsi_soal($question_id);

        // Load views
        $data['title'] = 'Kelola Opsi Soal';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/penilaian/opsi', $data);
        $this->load->view('templates/admin_footer');
    }

    // Simpan Opsi Soal
    public function simpan_opsi_soal($question_id) {
        // Get question details
        $this->db->select('soal.*, penilaian.id as assessment_id');
        $this->db->from('soal');
        $this->db->join('penilaian', 'penilaian.id = soal.id_penilaian', 'left');
        $this->db->where('soal.id', $question_id);
        $query = $this->db->get();
        $question = $query->row();

        // If question not found, show 404
        if (!$question) {
            show_404();
        }

        // Process based on question type
        if ($question->jenis_soal == 'benar_salah') {
            // For true/false questions
            $correct_option = $this->input->post('correct_option');

            // Delete existing options
            $this->db->where('id_soal', $question_id);
            $this->db->delete('pilihan_soal');

            // Add true option
            $true_option = array(
                'id_soal' => $question_id,
                'teks_pilihan' => 'Benar',
                'benar' => ($correct_option == 'true') ? 1 : 0,
                'dibuat_pada' => date('Y-m-d H:i:s')
            );
            $this->model_penilaian->tambah_opsi_soal($true_option);

            // Add false option
            $false_option = array(
                'id_soal' => $question_id,
                'teks_pilihan' => 'Salah',
                'benar' => ($correct_option == 'false') ? 1 : 0,
                'dibuat_pada' => date('Y-m-d H:i:s')
            );
            $this->model_penilaian->tambah_opsi_soal($false_option);
        } else {
            // For multiple choice questions
            $options = $this->input->post('options');
            $option_ids = $this->input->post('option_ids');
            $correct_option = $this->input->post('correct_option');

            // If no existing options, create new ones
            if (empty($option_ids)) {
                // Delete existing options (if any)
                $this->db->where('id_soal', $question_id);
                $this->db->delete('pilihan_soal');

                // Add new options
                foreach ($options as $index => $option_text) {
                    if (trim($option_text) != '') {
                        $option_data = array(
                            'id_soal' => $question_id,
                            'teks_pilihan' => $option_text,
                            'benar' => ($index == $correct_option) ? 1 : 0,
                            'dibuat_pada' => date('Y-m-d H:i:s')
                        );
                        $this->model_penilaian->tambah_opsi_soal($option_data);
                    }
                }
            } else {
                // Update existing options
                foreach ($option_ids as $index => $option_id) {
                    if (isset($options[$index]) && trim($options[$index]) != '') {
                        $option_data = array(
                            'teks_pilihan' => $options[$index],
                            'benar' => ($index == $correct_option) ? 1 : 0,
                            'diperbarui_pada' => date('Y-m-d H:i:s')
                        );
                        $this->model_penilaian->perbarui_opsi_soal($option_id, $option_data);
                    }
                }

                // Add new options (if any)
                for ($i = count($option_ids); $i < count($options); $i++) {
                    if (trim($options[$i]) != '') {
                        $option_data = array(
                            'id_soal' => $question_id,
                            'teks_pilihan' => $options[$i],
                            'benar' => ($i == $correct_option) ? 1 : 0,
                            'dibuat_pada' => date('Y-m-d H:i:s')
                        );
                        $this->model_penilaian->tambah_opsi_soal($option_data);
                    }
                }
            }
        }

        // Show success message
        $this->session->set_flashdata('success', 'Opsi soal berhasil disimpan.');
        redirect('admin/soal_penilaian/' . $question->assessment_id);
    }

    // Simpan Opsi Soal (camelCase version)
    public function simpanOpsiSoal($question_id) {
        // Call the original method to avoid code duplication
        return $this->simpan_opsi_soal($question_id);
    }

    // Dapatkan data chart untuk penilaian
    private function dapatkan_data_chart_penilaian() {
        // Get assessment type statistics
        $this->db->select('jenis_penilaian.nama as type_name, COUNT(penilaian.id) as count');
        $this->db->from('penilaian');
        $this->db->join('jenis_penilaian', 'jenis_penilaian.id = penilaian.id_jenis', 'left');
        $this->db->group_by('penilaian.id_jenis');
        $this->db->order_by('count', 'DESC');
        $type_stats = $this->db->get()->result();

        // Get completion statistics for each assessment
        $this->db->select('penilaian.judul, jenis_penilaian.nama as type_name,
                          COUNT(penilaian_pelamar.id) as total_assigned,
                          SUM(CASE WHEN penilaian_pelamar.status = "selesai" THEN 1 ELSE 0 END) as completed');
        $this->db->from('penilaian');
        $this->db->join('jenis_penilaian', 'jenis_penilaian.id = penilaian.id_jenis', 'left');
        $this->db->join('penilaian_pelamar', 'penilaian_pelamar.id_penilaian = penilaian.id', 'left');
        $this->db->group_by('penilaian.id');
        $this->db->having('total_assigned > 0');
        $this->db->order_by('total_assigned', 'DESC');
        $this->db->limit(5); // Top 5 assessments
        $completion_stats = $this->db->get()->result();

        return [
            'type_stats' => $type_stats,
            'completion_stats' => $completion_stats
        ];
    }

    // ========== FUNCTION NAMES INDONESIA ==========

    // Note: Function yang sudah ada tidak perlu duplikasi:
    // - penilaian() sudah ada di line 983
    // - tambah_soal() sudah ada di line 1411
    // - hasilPenilaian() sudah ada di line 1289
    // - hapus_penilaian() sudah ada di line 1136
    // - soal_penilaian() sudah ada di line 1269
    // - opsi_soal() sudah ada di line 1462

    public function edit_soal($question_id) {
        return $this->edit_question($question_id);
    }

    public function hapus_soal($question_id) {
        return $this->delete_question($question_id);
    }

    public function pratinjau_penilaian($assessment_id) {
        return $this->preview_assessment($assessment_id);
    }

    public function hasil_penilaian($assessment_id) {
        return $this->hasilPenilaian($assessment_id);
    }

    public function edit_question($question_id) {
        // Get question details
        $this->db->select('soal.*, penilaian.judul as assessment_title, penilaian.id as assessment_id');
        $this->db->from('soal');
        $this->db->join('penilaian', 'penilaian.id = soal.id_penilaian', 'left');
        $this->db->where('soal.id', $question_id);
        $query = $this->db->get();
        $data['question'] = $query->row();

        // If question not found, show 404
        if (!$data['question']) {
            show_404();
        }

        // Form validation rules
        $this->form_validation->set_rules('question_text', 'Teks Pertanyaan', 'trim|required');
        $this->form_validation->set_rules('question_type', 'Jenis Pertanyaan', 'trim|required');
        $this->form_validation->set_rules('points', 'Poin', 'trim|required|numeric');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Edit Soal';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/penilaian/edit_soal', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $question_data = array(
                'teks_soal' => $this->input->post('question_text'),
                'jenis_soal' => $this->input->post('question_type'),
                'poin' => $this->input->post('points'),
                'diperbarui_pada' => date('Y-m-d H:i:s')
            );

            // Handle image upload if provided
            if ($_FILES['question_image']['name']) {
                // Get current question data to delete old image
                $current_question = $this->model_penilaian->dapatkan_soal($question_id);

                // Make sure the directory exists and is writable (same as other uploads)
                $upload_path_full = FCPATH . 'uploads/gambar_soal/';

                if (!is_dir($upload_path_full)) {
                    if (!mkdir($upload_path_full, 0777, true)) {
                        $this->session->set_flashdata('error', 'Gagal membuat folder upload: ' . $upload_path_full);
                        redirect('admin/edit_question/' . $question_id);
                        return;
                    }
                }

                // Check if directory is writable
                if (!is_writable($upload_path_full)) {
                    $this->session->set_flashdata('error', 'Folder tidak dapat ditulis: ' . $upload_path_full);
                    redirect('admin/edit_question/' . $question_id);
                    return;
                }

                // Use absolute path for CodeIgniter upload library
                $config['upload_path'] = $upload_path_full;
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 4096; // 4MB
                $config['file_name'] = 'question_' . time() . '_' . rand(1000, 9999);
                $config['encrypt_name'] = FALSE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('question_image')) {
                    $upload_data = $this->upload->data();
                    $question_data['gambar_soal'] = $upload_data['file_name'];

                    // Delete old image if exists
                    if ($current_question && $current_question->gambar_soal) {
                        $old_file_path = $upload_path_full . $current_question->gambar_soal;
                        if (file_exists($old_file_path)) {
                            unlink($old_file_path);
                        }
                    }
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengunggah gambar: ' . $this->upload->display_errors());
                    redirect('admin/edit_question/' . $question_id);
                    return;
                }
            }

            // Update question data
            $result = $this->model_penilaian->perbarui_soal($question_id, $question_data);

            if ($result) {
                // Show success message
                $this->session->set_flashdata('success', 'Soal berhasil diperbarui.');
                redirect('admin/soal_penilaian/' . $data['question']->assessment_id);
            } else {
                // If update fails, show error message
                $this->session->set_flashdata('error', 'Gagal memperbarui soal. Silakan coba lagi.');
                redirect('admin/edit_question/' . $question_id);
            }
        }
    }

    public function delete_question($question_id) {
        // Check if user is logged in and is admin
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            redirect('auth/login');
        }

        // Get question details for redirect
        $this->db->select('soal.*, penilaian.id as assessment_id');
        $this->db->from('soal');
        $this->db->join('penilaian', 'penilaian.id = soal.id_penilaian', 'left');
        $this->db->where('soal.id', $question_id);
        $query = $this->db->get();
        $question = $query->row();

        if (!$question) {
            show_404();
        }

        // Delete question (this will also delete the image file via model)
        $result = $this->model_penilaian->hapus_soal($question_id);

        if ($result) {
            $this->session->set_flashdata('success', 'Soal berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus soal.');
        }

        redirect('admin/soal_penilaian/' . $question->assessment_id);
    }

    public function hapus_gambar_soal($question_id) {
        // Check if user is logged in and is admin
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            redirect('auth/login');
        }

        $result = $this->model_penilaian->hapus_gambar_soal($question_id);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Gambar soal berhasil dihapus.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus gambar soal.']);
        }
    }

    public function tetapkan_ke_pelamar($assessment_id) {
        return $this->tetapkanPenilaian($assessment_id);
    }

    // Manajemen Pengguna
    public function pengguna() {
        // Get all users
        $data['users'] = $this->model_pengguna->dapatkan_pengguna_semua();

        // Get user statistics for charts
        $data['user_stats'] = array(
            'admin_count' => $this->model_pengguna->hitung_pengguna_berdasarkan_role('admin'),
            'applicant_count' => $this->model_pengguna->hitung_pelamar(),
            'recruiter_count' => $this->model_pengguna->hitung_pengguna_berdasarkan_role('staff')
        );

        // Load views
        $data['title'] = 'Manajemen Pengguna';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/pengguna/index', $data);
        $this->load->view('templates/admin_footer');
    }

    // Tambah Pengguna
    public function tambah_pengguna() {
        // Load upload configuration
        $this->load->config('upload');

        // Form validation rules
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[pengguna.nama_pengguna]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[pengguna.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('role', 'Role', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah Pengguna Baru';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/pengguna/tambah', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Handle profile picture upload
            $profile_picture = '';
            if (!empty($_FILES['profile_picture']['name'])) {
                // Define upload path using absolute path
                $upload_dir = 'uploads/profile_pictures';
                $upload_path = str_replace('\\', '/', realpath(FCPATH . $upload_dir));

                // Create directory if it doesn't exist
                if (!is_dir($upload_path)) {
                    if (!mkdir($upload_path, 0777, true)) {
                        $this->session->set_flashdata('error', 'Gagal membuat direktori upload: ' . $upload_path);
                        redirect('admin/tambah_pengguna');
                        return;
                    }
                }

                // Ensure directory is writable
                if (!is_writable($upload_path)) {
                    if (!chmod($upload_path, 0777)) {
                        $this->session->set_flashdata('error', 'Direktori tidak dapat ditulis: ' . $upload_path);
                        redirect('admin/tambah_pengguna');
                        return;
                    }
                }

                // Load upload library
                $this->load->library('upload');

                // Get upload config and update path
                $config = $this->config->item('upload_config');
                $config['upload_path'] = $upload_path;

                // Initialize upload configuration
                $this->upload->initialize($config);

                // Attempt upload
                if ($this->upload->do_upload('profile_picture')) {
                    $upload_data = $this->upload->data();
                    $profile_picture = $upload_data['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', 'Gagal mengunggah gambar: ' . $error);
                    redirect('admin/tambah_pengguna');
                    return;
                }
            }

            // Prepare user data
            $user_data = array(
                'nama_pengguna' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role' => $this->input->post('role'),
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'status' => $this->input->post('status') ? 'aktif' : 'nonaktif',
                'foto_profil' => $profile_picture,
                'dibuat_pada' => date('Y-m-d H:i:s')
            );

            // Insert user data
            $user_id = $this->model_pengguna->tambah_pengguna($user_data);

            if ($user_id) {
                $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan.');
                redirect('admin/pengguna');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengguna. Silakan coba lagi.');
                redirect('admin/tambah_pengguna');
            }
        }
    }

    // Edit Pengguna
    public function edit_pengguna($id) {
        // Get user details
        $data['user'] = $this->model_pengguna->dapatkan_pengguna($id);

        // If user not found, show 404
        if (!$data['user']) {
            show_404();
        }

        // Form validation rules
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('role', 'Role', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Edit Pengguna';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/pengguna/edit', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $user_data = array(
                'nama_pengguna' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'nama_lengkap' => $this->input->post('full_name'),
                'telepon' => $this->input->post('phone'),
                'alamat' => $this->input->post('address'),
                'role' => $this->input->post('role'),
                'status' => $this->input->post('is_active') ? 'aktif' : 'nonaktif',
                'diperbarui_pada' => date('Y-m-d H:i:s')
            );

            // Upload profile picture if provided
            if ($_FILES['profile_picture']['name']) {
                // Simple approach - use relative path
                $upload_path = './uploads/profile_pictures/';

                // Create directory if not exists
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0755, true);
                }

                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'profile_' . time();

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('profile_picture')) {
                    // Delete old profile picture if exists
                    if (isset($data['user']->foto_profil) && $data['user']->foto_profil) {
                        $old_file = $upload_path_full . $data['user']->foto_profil;
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }

                    $upload_data = $this->upload->data();
                    $user_data['foto_profil'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin/edit_pengguna/' . $id);
                }
            }

            // Update user data
            $result = $this->model_pengguna->perbarui_pengguna($id, $user_data);

            if ($result) {
                // If user role changed to applicant, create applicant profile if not exists
                if ($user_data['role'] == 'pelamar') {
                    $profile = $this->model_pelamar->dapatkan_profil($id);

                    if (!$profile) {
                        $profile_data = array(
                            'id_pengguna' => $id,
                            'dibuat_pada' => date('Y-m-d H:i:s')
                        );
                        $this->model_pelamar->tambah_profil($profile_data);
                    }
                }

                // Show success message
                $this->session->set_flashdata('success', 'Informasi pengguna berhasil diperbarui.');
                redirect('admin/pengguna');
            } else {
                // If update fails, show error message
                $this->session->set_flashdata('error', 'Gagal memperbarui informasi pengguna. Silakan coba lagi.');
                redirect('admin/edit_pengguna/' . $id);
            }
        }
    }

    // Hapus Pengguna
    public function hapus_pengguna($id) {
        // Delete user
        $result = $this->model_pengguna->hapus_pengguna($id);

        if ($result) {
            // Show success message
            $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        } else {
            // If deletion fails, show error message
            $this->session->set_flashdata('error', 'Gagal menghapus pengguna. Silakan coba lagi.');
        }

        redirect('admin/pengguna');
    }

    // Aktifkan Pengguna
    public function aktifkan_pengguna($id) {
        $result = $this->model_pengguna->perbarui_pengguna($id, array('status' => 'aktif'));

        if ($result) {
            $this->session->set_flashdata('success', 'Pengguna berhasil diaktifkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan pengguna. Silakan coba lagi.');
        }

        redirect('admin/pengguna');
    }

    // Nonaktifkan Pengguna
    public function nonaktifkan_pengguna($id) {
        $result = $this->model_pengguna->perbarui_pengguna($id, array('status' => 'nonaktif'));

        if ($result) {
            $this->session->set_flashdata('success', 'Pengguna berhasil dinonaktifkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menonaktifkan pengguna. Silakan coba lagi.');
        }

        redirect('admin/pengguna');
    }

    // Reset Kata Sandi
    public function reset_kata_sandi($id) {
        // Generate random password
        $new_password = substr(md5(uniqid(rand(), true)), 0, 8);

        // Hash password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update user password
        $result = $this->model_pengguna->perbarui_password($id, $hashed_password);

        if ($result) {
            // Get user email
            $user = $this->model_pengguna->dapatkan_pengguna($id);

            // Send email with new password
            $this->load->library('email');

            $this->email->from('noreply@sirek.com', 'SIREK System');
            $this->email->to($user->email);
            $this->email->subject('Password Reset');
            $this->email->message('Your password has been reset. Your new password is: ' . $new_password);

            if ($this->email->send()) {
                $this->session->set_flashdata('success', 'Password berhasil direset dan dikirim ke email pengguna.');
            } else {
                $this->session->set_flashdata('success', 'Password berhasil direset tetapi gagal mengirim email. Password baru: ' . $new_password);
            }
        } else {
            $this->session->set_flashdata('error', 'Gagal mereset password. Silakan coba lagi.');
        }

        redirect('admin/pengguna');
    }

    // Lihat Profil Pelamar
    public function profil_pelamar($id) {
        // Get user details
        $data['user'] = $this->model_pengguna->dapatkan_pengguna($id);

        // If user not found or not an applicant, show 404
        if (!$data['user'] || $data['user']->role != 'pelamar') {
            show_404();
        }

        // Get applicant profile
        $data['profile'] = $this->model_pelamar->dapatkan_profil($id);

        // Get applicant documents
        $data['documents'] = $this->model_dokumen->dapatkan_dokumen_pelamar($id);

        // Load views
        $data['title'] = 'Profil Pelamar';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/pengguna/profil_pelamar', $data);
        $this->load->view('templates/admin_footer');
    }

    // Lihat Profil Pelamar (alias untuk URL dengan camelCase)
    public function profilPelamar($id) {
        // Get user details
        $data['user'] = $this->model_pengguna->dapatkan_pengguna($id);

        // If user not found or not an applicant, show 404
        if (!$data['user'] || $data['user']->role != 'pelamar') {
            show_404();
        }

        // Get applicant profile
        $data['profile'] = $this->model_pelamar->dapatkan_profil($id);

        // Get applicant documents
        $data['documents'] = $this->model_dokumen->dapatkan_dokumen_pelamar($id);

        // Load views
        $data['title'] = 'Profil Pelamar';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/pengguna/profil_pelamar', $data);
        $this->load->view('templates/admin_footer');
    }

    // Lihat Lamaran Pelamar
    public function lamaran_pelamar($id) {
        // Get user details
        $data['user'] = $this->model_pengguna->dapatkan_pengguna($id);

        // If user not found or not an applicant, show 404
        if (!$data['user'] || $data['user']->role != 'pelamar') {
            show_404();
        }

        // Get applicant applications
        $data['applications'] = $this->model_lamaran->dapatkan_lamaran_pelamar($id);

        // Load views
        $data['title'] = 'Lamaran Pelamar';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/pengguna/lamaran_pelamar', $data);
        $this->load->view('templates/admin_footer');
    }

    // Export Lamaran Pelamar ke Excel
    public function export_applicant_applications($id) {
        // Load helper
        $this->load->helper('phpspreadsheet');

        // Get user details
        $user = $this->model_pengguna->dapatkan_pengguna($id);

        // If user not found or not an applicant, show 404
        if (!$user || $user->role != 'pelamar') {
            show_404();
        }

        // Get applicant applications
        $applications = $this->model_lamaran->dapatkan_lamaran_pelamar($id);

        // Check if PhpSpreadsheet is available
        if (!load_phpspreadsheet()) {
            $this->session->set_flashdata('error', 'PhpSpreadsheet library tidak tersedia. Silakan hubungi administrator.');
            redirect('admin/lamaran_pelamar/' . $id);
        }

        // Create a new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set spreadsheet metadata
        $spreadsheet->getProperties()
            ->setCreator('Sistem Rekrutmen')
            ->setLastModifiedBy('Admin')
            ->setTitle('Daftar Lamaran ' . $user->nama_lengkap)
            ->setSubject('Daftar Lamaran Pelamar')
            ->setDescription('Daftar lamaran yang diajukan oleh ' . $user->nama_lengkap);

        // Set column headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Lowongan');
        $sheet->setCellValue('C1', 'Tipe Pekerjaan');
        $sheet->setCellValue('D1', 'Lokasi');
        $sheet->setCellValue('E1', 'Tanggal Lamaran');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Penilaian');

        // Style the header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4E73DF'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);

        // Add data rows
        $row = 2;
        $no = 1;

        foreach ($applications as $application) {
            // Get assessment counts
            $assessment_count = $this->model_penilaian->hitung_penilaian_pelamar($application->id);
            $completed_count = $this->model_penilaian->hitung_penilaian_selesai($application->id);

            // Format job type
            $job_type = $application->job_type == 'full_time' ? 'Full Time' :
                       ($application->job_type == 'part_time' ? 'Part Time' :
                       ($application->job_type == 'contract' ? 'Kontrak' : $application->job_type));

            // Format assessment status
            $assessment_status = ($assessment_count > 0) ?
                                $completed_count . '/' . $assessment_count . ' Selesai' :
                                'Tidak Ada';

            // Add data to sheet
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $application->job_title);
            $sheet->setCellValue('C' . $row, $job_type);
            $sheet->setCellValue('D' . $row, $application->location);
            $sheet->setCellValue('E' . $row, date('d M Y', strtotime($application->application_date)));
            $sheet->setCellValue('F' . $row, ucfirst($application->status));
            $sheet->setCellValue('G' . $row, $assessment_status);

            // Style for data rows
            $rowStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];

            $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray($rowStyle);

            $row++;
            $no++;
        }

        // Set title above the table
        $sheet->insertNewRowBefore(1, 2);
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'DAFTAR LAMARAN ' . strtoupper($user->nama_lengkap));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Download the file
        $filename = 'Lamaran_' . str_replace(' ', '_', $user->nama_lengkap) . '_' . date('Y-m-d') . '.xlsx';
        download_excel_file($spreadsheet, $filename);
    }

    // Lihat Lowongan Rekruter
    public function lowongan_rekruter($id) {
        // Get user details
        $data['user'] = $this->model_pengguna->dapatkan_pengguna($id);

        // If user not found or not a recruiter, show 404
        if (!$data['user'] || ($data['user']->role != 'recruiter' && $data['user']->role != 'staff')) {
            show_404();
        }

        // Get recruiter jobs
        $data['jobs'] = $this->model_lowongan->dapatkan_lowongan_recruiter($id);

        // Load views
        $data['title'] = 'Lowongan yang Dikelola';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/pengguna/pekerjaan_rekruter', $data);
        $this->load->view('templates/admin_footer');
    }

    // Blog Management
    public function blog() {
        // Get all posts
        $data['posts'] = $this->model_blog->dapatkan_artikel_semua();

        // Get blog categories
        $data['categories'] = $this->model_kategori->dapatkan_kategori_blog();

        // Load views
        $data['title'] = 'Manajemen Blog';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/blog/index', $data);
        $this->load->view('templates/admin_footer');
    }

    // ========== LAPORAN ==========

    // Laporan Dashboard
    public function laporan() {
        // Load models for reports
        $this->load->model('model_laporan');

        // Get summary data for dashboard
        $data['summary'] = $this->model_laporan->dapatkan_ringkasan_laporan();

        // Load views
        $data['title'] = 'Laporan & Statistik';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/laporan/index', $data);
        $this->load->view('templates/admin_footer');
    }

    // Laporan Lowongan
    public function laporan_lowongan() {
        $this->load->model('model_laporan');

        // Get filter parameters
        $filters = [
            'periode' => $this->input->get('periode') ?: 'bulan',
            'tanggal_mulai' => $this->input->get('tanggal_mulai'),
            'tanggal_selesai' => $this->input->get('tanggal_selesai'),
            'kategori' => $this->input->get('kategori'),
            'status' => $this->input->get('status'),
            'lokasi' => $this->input->get('lokasi')
        ];

        // Get report data
        $data['lowongan'] = $this->model_laporan->laporan_lowongan($filters);
        $data['statistik_lowongan'] = $this->model_laporan->statistik_lowongan($filters);
        $data['filters'] = $filters;

        // Get filter options
        $this->load->model('model_kategori');
        $data['categories'] = $this->model_kategori->dapatkan_kategori_lowongan();
        $data['locations'] = $this->model_laporan->dapatkan_lokasi_lowongan();

        $data['title'] = 'Laporan Lowongan Pekerjaan';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/laporan/lowongan', $data);
        $this->load->view('templates/admin_footer');
    }

    // Laporan Lamaran
    public function laporan_lamaran() {
        $this->load->model('model_laporan');

        // Get filter parameters
        $filters = [
            'periode' => $this->input->get('periode') ?: 'bulan',
            'tanggal_mulai' => $this->input->get('tanggal_mulai'),
            'tanggal_selesai' => $this->input->get('tanggal_selesai'),
            'status' => $this->input->get('status'),
            'lowongan' => $this->input->get('lowongan')
        ];

        // Get report data
        $data['lamaran'] = $this->model_laporan->laporan_lamaran($filters);
        $data['statistik_lamaran'] = $this->model_laporan->statistik_lamaran($filters);
        $data['conversion_rate'] = $this->model_laporan->conversion_rate_lamaran($filters);
        $data['filters'] = $filters;

        // Get filter options
        $data['lowongan_list'] = $this->model_laporan->dapatkan_daftar_lowongan();

        $data['title'] = 'Laporan Lamaran';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/laporan/lamaran', $data);
        $this->load->view('templates/admin_footer');
    }

    // Laporan Pelamar
    public function laporan_pelamar() {
        $this->load->model('model_laporan');

        // Get filter parameters
        $filters = [
            'periode' => $this->input->get('periode') ?: 'bulan',
            'tanggal_mulai' => $this->input->get('tanggal_mulai'),
            'tanggal_selesai' => $this->input->get('tanggal_selesai'),
            'lokasi' => $this->input->get('lokasi'),
            'pendidikan' => $this->input->get('pendidikan')
        ];

        // Get report data
        $data['pelamar'] = $this->model_laporan->laporan_pelamar($filters);
        $data['statistik_pelamar'] = $this->model_laporan->statistik_pelamar($filters);
        $data['aktivitas_login'] = $this->model_laporan->aktivitas_login_pelamar($filters);
        $data['filters'] = $filters;

        $data['title'] = 'Laporan Pelamar';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/laporan/pelamar', $data);
        $this->load->view('templates/admin_footer');
    }

    // Laporan Penilaian
    public function laporan_penilaian() {
        $this->load->model('model_laporan');

        // Get filter parameters
        $filters = [
            'periode' => $this->input->get('periode') ?: 'semua',
            'tanggal_mulai' => $this->input->get('tanggal_mulai'),
            'tanggal_selesai' => $this->input->get('tanggal_selesai'),
            'penilaian' => $this->input->get('penilaian'),
            'lowongan' => $this->input->get('lowongan')
        ];

        // Get report data
        $data['hasil_penilaian'] = $this->model_laporan->laporan_hasil_penilaian($filters);
        $data['statistik_penilaian'] = $this->model_laporan->statistik_penilaian($filters);
        $data['tingkat_kelulusan'] = $this->model_laporan->tingkat_kelulusan_penilaian($filters);
        $data['filters'] = $filters;

        // Get filter options
        $data['penilaian_list'] = $this->model_laporan->dapatkan_daftar_penilaian();
        $data['lowongan_list'] = $this->model_laporan->dapatkan_daftar_lowongan();

        $data['title'] = 'Laporan Penilaian';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/laporan/penilaian', $data);
        $this->load->view('templates/admin_footer');
    }

    // Perbaiki data waktu mulai yang kosong
    public function perbaiki_waktu_mulai() {
        $this->load->model('model_penilaian');

        $result = $this->model_penilaian->perbaiki_waktu_mulai_kosong();

        $this->session->set_flashdata('success',
            'Berhasil memperbaiki data waktu mulai: ' .
            $result['sedang_mengerjakan'] . ' data sedang mengerjakan, ' .
            $result['selesai'] . ' data selesai'
        );

        redirect('admin/laporan_penilaian');
    }

    // Export Laporan
    public function export_laporan() {
        $this->load->model('model_laporan');

        $jenis = $this->input->get('jenis');
        $format = $this->input->get('format') ?: 'excel';

        // Get filters from GET parameters
        $filters = [
            'periode' => $this->input->get('periode') ?: 'bulan',
            'tanggal_mulai' => $this->input->get('tanggal_mulai'),
            'tanggal_selesai' => $this->input->get('tanggal_selesai'),
            'kategori' => $this->input->get('kategori'),
            'status' => $this->input->get('status'),
            'lokasi' => $this->input->get('lokasi'),
            'lowongan' => $this->input->get('lowongan'),
            'penilaian' => $this->input->get('penilaian'),
            'pendidikan' => $this->input->get('pendidikan')
        ];

        switch ($jenis) {
            case 'lowongan':
                $data = $this->model_laporan->laporan_lowongan($filters);
                $filename = 'Laporan_Lowongan_' . date('Y-m-d_H-i-s');
                $this->_export_lowongan($data, $filename, $format);
                break;
            case 'lamaran':
                $data = $this->model_laporan->laporan_lamaran($filters);
                $filename = 'Laporan_Lamaran_' . date('Y-m-d_H-i-s');
                $this->_export_lamaran($data, $filename, $format);
                break;
            case 'pelamar':
                $data = $this->model_laporan->laporan_pelamar($filters);
                $filename = 'Laporan_Pelamar_' . date('Y-m-d_H-i-s');
                $this->_export_pelamar($data, $filename, $format);
                break;
            case 'penilaian':
                $data = $this->model_laporan->laporan_hasil_penilaian($filters);
                $filename = 'Laporan_Penilaian_' . date('Y-m-d_H-i-s');
                $this->_export_penilaian($data, $filename, $format);
                break;
            default:
                show_404();
        }
    }

    private function _export_lowongan($data, $filename, $format) {
        if ($format == 'excel') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
            header('Cache-Control: max-age=0');

            echo '<table border="1">';
            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Judul Lowongan</th>';
            echo '<th>Kategori</th>';
            echo '<th>Lokasi</th>';
            echo '<th>Status</th>';
            echo '<th>Tanggal Dibuat</th>';
            echo '<th>Batas Waktu</th>';
            echo '</tr>';

            $no = 1;
            foreach ($data as $row) {
                echo '<tr>';
                echo '<td>' . $no++ . '</td>';
                echo '<td>' . $row->judul . '</td>';
                echo '<td>' . ($row->kategori_nama ?: 'Tidak ada kategori') . '</td>';
                echo '<td>' . $row->lokasi . '</td>';
                echo '<td>' . ucfirst($row->status) . '</td>';
                echo '<td>' . date('d/m/Y', strtotime($row->dibuat_pada)) . '</td>';
                echo '<td>' . date('d/m/Y', strtotime($row->batas_waktu)) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }

    private function _export_lamaran($data, $filename, $format) {
        if ($format == 'excel') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
            header('Cache-Control: max-age=0');

            echo '<table border="1">';
            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Pelamar</th>';
            echo '<th>Lowongan</th>';
            echo '<th>Status</th>';
            echo '<th>Tanggal Lamaran</th>';
            echo '</tr>';

            $no = 1;
            foreach ($data as $row) {
                echo '<tr>';
                echo '<td>' . $no++ . '</td>';
                echo '<td>' . $row->pelamar_nama . '</td>';
                echo '<td>' . $row->lowongan_judul . '</td>';
                echo '<td>' . ucfirst($row->status) . '</td>';
                echo '<td>' . date('d/m/Y H:i', strtotime($row->tanggal_lamaran)) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }

    private function _export_pelamar($data, $filename, $format) {
        if ($format == 'excel') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
            header('Cache-Control: max-age=0');

            echo '<table border="1">';
            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Nama Lengkap</th>';
            echo '<th>Email</th>';
            echo '<th>Pendidikan</th>';
            echo '<th>Total Lamaran</th>';
            echo '<th>Tanggal Daftar</th>';
            echo '<th>Last Login</th>';
            echo '</tr>';

            $no = 1;
            foreach ($data as $row) {
                echo '<tr>';
                echo '<td>' . $no++ . '</td>';
                echo '<td>' . $row->nama_lengkap . '</td>';
                echo '<td>' . $row->email . '</td>';
                echo '<td>' . ($row->pendidikan ?: '-') . '</td>';
                echo '<td>' . $row->total_lamaran . '</td>';
                echo '<td>' . date('d/m/Y', strtotime($row->dibuat_pada)) . '</td>';
                echo '<td>' . ($row->last_login ? date('d/m/Y H:i', strtotime($row->last_login)) : 'Belum pernah') . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }

    private function _export_penilaian($data, $filename, $format) {
        if ($format == 'excel') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
            header('Cache-Control: max-age=0');

            echo '<table border="1">';
            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Pelamar</th>';
            echo '<th>Penilaian</th>';
            echo '<th>Lowongan</th>';
            echo '<th>Nilai</th>';
            echo '<th>Status</th>';
            echo '<th>Waktu Pengerjaan (menit)</th>';
            echo '<th>Tanggal Mulai</th>';
            echo '<th>Tanggal Selesai</th>';
            echo '</tr>';

            $no = 1;
            foreach ($data as $row) {
                echo '<tr>';
                echo '<td>' . $no++ . '</td>';
                echo '<td>' . ($row->pelamar_nama ?: '-') . '</td>';
                echo '<td>' . ($row->penilaian_judul ?: '-') . '</td>';
                echo '<td>' . ($row->lowongan_judul ?: '-') . '</td>';
                echo '<td>' . ($row->status == 'selesai' && isset($row->nilai) ? $row->nilai : '-') . '</td>';
                echo '<td>' . ucfirst($row->status) . '</td>';
                echo '<td>' . (isset($row->waktu_pengerjaan) && $row->waktu_pengerjaan ? $row->waktu_pengerjaan : '-') . '</td>';
                echo '<td>' . (isset($row->waktu_mulai) && $row->waktu_mulai ? date('d/m/Y H:i', strtotime($row->waktu_mulai)) : '-') . '</td>';
                echo '<td>' . (isset($row->waktu_selesai) && $row->waktu_selesai ? date('d/m/Y H:i', strtotime($row->waktu_selesai)) : '-') . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }

    // Tambah Artikel Blog
    public function tambah_artikel() {
        // Get blog categories
        $data['categories'] = $this->model_kategori->dapatkan_kategori_blog();

        // Form validation rules
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required|is_unique[blog_posts.slug]');
        $this->form_validation->set_rules('content', 'Content', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Tambah Artikel Baru';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/blog/add', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $post_data = array(
                'title' => $this->input->post('title'),
                'slug' => $this->input->post('slug'),
                'content' => $this->input->post('content'),
                'status' => $this->input->post('status'),
                'author_id' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s')
            );

            // Upload featured image if provided
            if ($_FILES['featured_image']['name']) {
                // Make sure the directory exists and is writable
                $upload_path = FCPATH . 'uploads/blog_images/';
                if (!is_dir($upload_path)) {
                    if (!mkdir($upload_path, 0777, true)) {
                        $error = "Failed to create directory: " . $upload_path;
                        $this->session->set_flashdata('error', $error);
                        redirect('admin/tambah_artikel');
                    }
                }

                // Check if directory is writable
                if (!is_writable($upload_path)) {
                    $error = "Directory is not writable: " . $upload_path;
                    $this->session->set_flashdata('error', $error);
                    redirect('admin/tambah_artikel');
                }

                $upload_path_full = FCPATH . 'uploads/blog_images/';
                if (!is_dir($upload_path_full)) {
                    mkdir($upload_path_full, 0777, true);
                }

                $config['upload_path'] = realpath($upload_path_full) . '/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'blog_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('featured_image')) {
                    $upload_data = $this->upload->data();
                    $post_data['featured_image'] = $upload_data['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect('admin/tambah_artikel');
                }
            }

            // Insert post data
            $post_id = $this->model_blog->tambah_artikel($post_data);

            if ($post_id) {
                // Add post categories
                $categories = $this->input->post('categories');
                if (!empty($categories)) {
                    foreach ($categories as $category_id) {
                        $this->model_blog->tambah_kategori_artikel($post_id, $category_id);
                    }
                }

                // Show success message
                $this->session->set_flashdata('success', 'Artikel baru berhasil ditambahkan.');
                redirect('admin/blog');
            } else {
                // If insertion fails, show error message
                $this->session->set_flashdata('error', 'Gagal menambahkan artikel. Silakan coba lagi.');
                redirect('admin/tambah_artikel');
            }
        }
    }

    // Edit Artikel Blog
    public function edit_artikel($id) {
        // Get post details
        $data['post'] = $this->model_blog->dapatkan_artikel($id);

        // If post not found, show 404
        if (!$data['post']) {
            show_404();
        }

        // Get blog categories
        $data['categories'] = $this->model_kategori->dapatkan_kategori_blog();

        // Get post categories
        $data['post_categories'] = $this->model_blog->dapatkan_kategori_artikel($id);
        $data['selected_categories'] = array();
        foreach ($data['post_categories'] as $category) {
            $data['selected_categories'][] = $category->id;
        }

        // Form validation rules
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required');
        $this->form_validation->set_rules('content', 'Content', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Edit Artikel';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/blog/edit', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $post_data = array(
                'title' => $this->input->post('title'),
                'slug' => $this->input->post('slug'),
                'content' => $this->input->post('content'),
                'status' => $this->input->post('status'),
                'diperbarui_pada' => date('Y-m-d H:i:s')
            );

            // Upload featured image if provided
            if ($_FILES['featured_image']['name']) {
                // Make sure the directory exists and is writable
                $upload_path = FCPATH . 'uploads/blog_images/';
                if (!is_dir($upload_path)) {
                    if (!mkdir($upload_path, 0777, true)) {
                        $error = "Failed to create directory: " . $upload_path;
                        $this->session->set_flashdata('error', $error);
                        redirect('admin/edit_artikel/' . $id);
                    }
                }

                // Check if directory is writable
                if (!is_writable($upload_path)) {
                    $error = "Directory is not writable: " . $upload_path;
                    $this->session->set_flashdata('error', $error);
                    redirect('admin/edit_artikel/' . $id);
                }

                $config['upload_path'] = './uploads/blog_images/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'blog_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('featured_image')) {
                    // Delete old featured image if exists
                    if ($data['post']->featured_image) {
                        $old_file = './uploads/blog_images/' . $data['post']->featured_image;
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }

                    $upload_data = $this->upload->data();
                    $post_data['featured_image'] = $upload_data['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect('admin/edit_artikel/' . $id);
                }
            }

            // Update post data
            $result = $this->model_blog->perbarui_artikel($id, $post_data);

            if ($result) {
                // Update post categories
                $this->model_blog->hapus_semua_kategori_artikel($id);
                $categories = $this->input->post('categories');
                if (!empty($categories)) {
                    foreach ($categories as $category_id) {
                        $this->model_blog->tambah_kategori_artikel($id, $category_id);
                    }
                }

                // Show success message
                $this->session->set_flashdata('success', 'Artikel berhasil diperbarui.');
                redirect('admin/blog');
            } else {
                // If update fails, show error message
                $this->session->set_flashdata('error', 'Gagal memperbarui artikel. Silakan coba lagi.');
                redirect('admin/edit_artikel/' . $id);
            }
        }
    }

    // Hapus Artikel Blog
    public function hapus_artikel($id) {
        // Get post details
        $post = $this->model_blog->dapatkan_artikel($id);

        // If post not found, show 404
        if (!$post) {
            show_404();
        }

        // Delete post
        $result = $this->model_blog->hapus_artikel($id);

        if ($result) {
            // Delete featured image if exists
            if ($post->featured_image) {
                $file_path = './uploads/blog_images/' . $post->featured_image;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            // Show success message
            $this->session->set_flashdata('success', 'Artikel berhasil dihapus.');
        } else {
            // If deletion fails, show error message
            $this->session->set_flashdata('error', 'Gagal menghapus artikel. Silakan coba lagi.');
        }

        redirect('admin/blog');
    }

    // Publikasi Artikel Blog
    public function publikasi_artikel($id) {
        $result = $this->model_blog->perbarui_artikel($id, array('status' => 'published'));

        if ($result) {
            $this->session->set_flashdata('success', 'Artikel berhasil dipublikasikan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mempublikasikan artikel. Silakan coba lagi.');
        }

        redirect('admin/blog');
    }

    // Batalkan Publikasi Artikel Blog
    public function batalkan_publikasi_artikel($id) {
        $result = $this->model_blog->perbarui_artikel($id, array('status' => 'draft'));

        if ($result) {
            $this->session->set_flashdata('success', 'Artikel berhasil dijadikan draft.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menjadikan artikel sebagai draft. Silakan coba lagi.');
        }

        redirect('admin/blog');
    }

    // Tambah Kategori Blog
    public function tambah_kategori_blog() {
        // Form validation rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required|is_unique[blog_categories.slug]');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show error message
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/blog');
        } else {
            // Get form data
            $category_data = array(
                'name' => $this->input->post('name'),
                'slug' => $this->input->post('slug'),
                'description' => $this->input->post('description')
            );

            // Insert category data
            $result = $this->model_kategori->tambah_kategori_blog($category_data);

            if ($result) {
                // Show success message
                $this->session->set_flashdata('success', 'Kategori blog berhasil ditambahkan.');
            } else {
                // If insertion fails, show error message
                $this->session->set_flashdata('error', 'Gagal menambahkan kategori blog. Silakan coba lagi.');
            }

            redirect('admin/blog');
        }
    }

    // Edit Kategori Blog
    public function edit_kategori_blog() {
        // Get category ID
        $id = $this->input->post('id');

        // Form validation rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show error message
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/blog');
        } else {
            // Get form data
            $category_data = array(
                'name' => $this->input->post('name'),
                'slug' => $this->input->post('slug'),
                'description' => $this->input->post('description')
            );

            // Update category data
            $result = $this->model_kategori->perbarui_kategori_blog($id, $category_data);

            if ($result) {
                // Show success message
                $this->session->set_flashdata('success', 'Kategori blog berhasil diperbarui.');
            } else {
                // If update fails, show error message
                $this->session->set_flashdata('error', 'Gagal memperbarui kategori blog. Silakan coba lagi.');
            }

            redirect('admin/blog');
        }
    }

    // Hapus Kategori Blog
    public function hapus_kategori_blog($id) {
        // Delete category
        $result = $this->model_kategori->hapus_kategori_blog($id);

        if ($result) {
            // Show success message
            $this->session->set_flashdata('success', 'Kategori blog berhasil dihapus.');
        } else {
            // If deletion fails, show error message
            $this->session->set_flashdata('error', 'Gagal menghapus kategori blog. Silakan coba lagi.');
        }

        redirect('admin/blog');
    }
}

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

        // Get recent applications
        $data['recent_applications'] = $this->model_lamaran->dapatkan_lamaran_terbaru(5);

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
                'title' => $this->input->post('title'),
                'category_id' => $this->input->post('category_id'),
                'description' => $this->input->post('description'),
                'requirements' => $this->input->post('requirements'),
                'responsibilities' => $this->input->post('responsibilities'),
                'location' => $this->input->post('location'),
                'job_type' => $this->input->post('job_type'),
                'salary_range' => $this->input->post('salary_range'),
                'deadline' => $this->input->post('deadline'),
                'vacancies' => $this->input->post('vacancies'),
                'featured' => $this->input->post('featured') ? 1 : 0,
                'status' => $this->input->post('status'),
                'created_by' => $this->session->userdata('user_id')
            );

            // Insert job data
            $job_id = $this->model_lowongan->tambah_lowongan($job_data);

            if ($job_id) {
                // Show success message
                $this->session->set_flashdata('success', 'Lowongan berhasil ditambahkan.');
                redirect('admin/lowongan');
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
                'title' => $this->input->post('title'),
                'category_id' => $this->input->post('category_id'),
                'description' => $this->input->post('description'),
                'requirements' => $this->input->post('requirements'),
                'responsibilities' => $this->input->post('responsibilities'),
                'location' => $this->input->post('location'),
                'job_type' => $this->input->post('job_type'),
                'salary_range' => $this->input->post('salary_range'),
                'deadline' => $this->input->post('deadline'),
                'vacancies' => $this->input->post('vacancies'),
                'featured' => $this->input->post('featured') ? 1 : 0,
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
        // Get search and filter parameters
        $search = $this->input->get('search');
        $status = $this->input->get('status');
        $job_id = $this->input->get('job_id');

        // Set up pagination
        $config['base_url'] = base_url('admin/lamaran');
        $config['total_rows'] = $this->model_lamaran->hitung_lamaran();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';

        // Pagination styling
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'Pertama';
        $config['last_link'] = 'Terakhir';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        // Initialize pagination
        $this->pagination->initialize($config);

        // Get applications for current page
        $page = $this->input->get('page') ? $this->input->get('page') : 0;

        // For now, get all applications (we'll implement filtering later)
        $data['applications'] = $this->model_lamaran->dapatkan_lamaran_semua();

        // Load views
        $data['title'] = 'Manajemen Lamaran';
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/applications/index', $data);
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
        $data['profile'] = $this->model_pelamar->dapatkan_profil($data['application']->applicant_id);

        // Get assessment results
        $data['assessments'] = $this->model_penilaian->dapatkan_penilaian_pelamar($id);

        // Load views
        $data['title'] = 'Detail Lamaran';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/applications/details', $data);
        $this->load->view('templates/admin_footer');
    }

    public function perbarui_status_lamaran() {
        // Get form data
        $application_id = $this->input->post('application_id');
        $status = $this->input->post('status');

        // Update application status
        $result = $this->model_lamaran->perbarui_status($application_id, $status);

        if ($result) {
            // Show success message
            $this->session->set_flashdata('success', 'Status lamaran berhasil diperbarui.');
        } else {
            // If update fails, show error message
            $this->session->set_flashdata('error', 'Gagal memperbarui status lamaran. Silakan coba lagi.');
        }

        redirect('admin/detail_lamaran/' . $application_id);
    }

    // Manajemen Penilaian
    public function penilaian() {
        // Get all assessments
        $data['assessments'] = $this->model_penilaian->dapatkan_semua_penilaian();

        // Get assessment types for filter
        $data['assessment_types'] = $this->model_penilaian->dapatkan_jenis_penilaian();

        // Get jobs for filter
        $data['jobs'] = $this->model_lowongan->dapatkan_lowongan_aktif(100, 0);

        // Set up pagination
        $config['base_url'] = base_url('admin/penilaian');
        $config['total_rows'] = count($data['assessments']);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';

        // Pagination styling
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'Pertama';
        $config['last_link'] = 'Terakhir';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        // Initialize pagination
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // Load views
        $data['title'] = 'Manajemen Penilaian';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/assessments/index', $data);
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
            $this->load->view('admin/assessments/add', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $assessment_data = array(
                'title' => $this->input->post('title'),
                'type_id' => $this->input->post('assessment_type_id'),
                'description' => $this->input->post('description'),
                'instructions' => $this->input->post('instructions'),
                'time_limit' => $this->input->post('time_limit'),
                'passing_score' => $this->input->post('passing_score'),
                'max_attempts' => $this->input->post('max_attempts'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s')
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

        // Load views
        $data['title'] = 'Kelola Soal Penilaian';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/assessments/questions', $data);
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
            $this->load->view('admin/assessments/add_question', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $question_data = array(
                'assessment_id' => $assessment_id,
                'question_text' => $this->input->post('question_text'),
                'question_type' => $this->input->post('question_type'),
                'points' => $this->input->post('points'),
                'created_at' => date('Y-m-d H:i:s')
            );

            // Insert question data
            $question_id = $this->model_penilaian->tambah_soal($question_data);

            if ($question_id) {
                // If question type is multiple choice or true/false, add options
                if ($this->input->post('question_type') == 'multiple_choice' || $this->input->post('question_type') == 'true_false') {
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
        $this->db->select('questions.*, assessments.title as assessment_title, assessments.id as assessment_id');
        $this->db->from('questions');
        $this->db->join('assessments', 'assessments.id = questions.assessment_id', 'left');
        $this->db->where('questions.id', $question_id);
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
        $this->load->view('admin/assessments/options', $data);
        $this->load->view('templates/admin_footer');
    }

    // Simpan Opsi Soal
    public function simpan_opsi_soal($question_id) {
        // Get question details
        $this->db->select('questions.*, assessments.id as assessment_id');
        $this->db->from('questions');
        $this->db->join('assessments', 'assessments.id = questions.assessment_id', 'left');
        $this->db->where('questions.id', $question_id);
        $query = $this->db->get();
        $question = $query->row();

        // If question not found, show 404
        if (!$question) {
            show_404();
        }

        // Process based on question type
        if ($question->question_type == 'true_false') {
            // For true/false questions
            $correct_option = $this->input->post('correct_option');

            // Delete existing options
            $this->db->where('question_id', $question_id);
            $this->db->delete('question_options');

            // Add true option
            $true_option = array(
                'question_id' => $question_id,
                'option_text' => 'Benar',
                'is_correct' => ($correct_option == 'true') ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->model_penilaian->tambah_opsi_soal($true_option);

            // Add false option
            $false_option = array(
                'question_id' => $question_id,
                'option_text' => 'Salah',
                'is_correct' => ($correct_option == 'false') ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s')
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
                $this->db->where('question_id', $question_id);
                $this->db->delete('question_options');

                // Add new options
                foreach ($options as $index => $option_text) {
                    if (trim($option_text) != '') {
                        $option_data = array(
                            'question_id' => $question_id,
                            'option_text' => $option_text,
                            'is_correct' => ($index == $correct_option) ? 1 : 0,
                            'created_at' => date('Y-m-d H:i:s')
                        );
                        $this->model_penilaian->tambah_opsi_soal($option_data);
                    }
                }
            } else {
                // Update existing options
                foreach ($option_ids as $index => $option_id) {
                    if (isset($options[$index]) && trim($options[$index]) != '') {
                        $option_data = array(
                            'option_text' => $options[$index],
                            'is_correct' => ($index == $correct_option) ? 1 : 0,
                            'updated_at' => date('Y-m-d H:i:s')
                        );
                        $this->model_penilaian->perbarui_opsi_soal($option_id, $option_data);
                    }
                }

                // Add new options (if any)
                for ($i = count($option_ids); $i < count($options); $i++) {
                    if (trim($options[$i]) != '') {
                        $option_data = array(
                            'question_id' => $question_id,
                            'option_text' => $options[$i],
                            'is_correct' => ($i == $correct_option) ? 1 : 0,
                            'created_at' => date('Y-m-d H:i:s')
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

    // Manajemen Pengguna
    public function pengguna() {
        // Get search and filter parameters
        $search = $this->input->get('search');
        $role = $this->input->get('role');
        $status = $this->input->get('status');

        // Set up pagination
        $config['base_url'] = base_url('admin/pengguna');
        $config['total_rows'] = $this->model_pengguna->hitung_pengguna();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';

        // Pagination styling
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'Pertama';
        $config['last_link'] = 'Terakhir';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        // Initialize pagination
        $this->pagination->initialize($config);

        // Get users for current page
        $page = $this->input->get('page') ? $this->input->get('page') : 0;

        // For now, get all users (we'll implement filtering later)
        $data['users'] = $this->model_pengguna->dapatkan_pengguna_semua();

        // Get user statistics for charts
        $data['user_stats'] = array(
            'admin_count' => $this->model_pengguna->hitung_pengguna_berdasarkan_peran('admin'),
            'applicant_count' => $this->model_pengguna->hitung_pelamar(),
            'recruiter_count' => $this->model_pengguna->hitung_pengguna_berdasarkan_peran('staff')
        );

        // Load views
        $data['title'] = 'Manajemen Pengguna';
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/users/index', $data);
        $this->load->view('templates/admin_footer');
    }

    // Tambah Pengguna
    public function tambah_pengguna() {
        // Form validation rules
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('role', 'Role', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Tambah Pengguna Baru';
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/users/add', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $user_data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'full_name' => $this->input->post('full_name'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'role' => $this->input->post('role'),
                'status' => $this->input->post('is_active') ? 'active' : 'inactive',
                'created_at' => date('Y-m-d H:i:s')
            );

            // Upload profile picture if provided
            if ($_FILES['profile_picture']['name']) {
                // Make sure the directory exists and is writable
                $upload_path = FCPATH . 'uploads/profile_pictures/';
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'profile_' . time();

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('profile_picture')) {
                    $upload_data = $this->upload->data();
                    $user_data['profile_picture'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin/tambah_pengguna');
                }
            }

            // Insert user data
            $user_id = $this->model_pengguna->tambah_pengguna($user_data);

            if ($user_id) {
                // If user is applicant, create applicant profile
                if ($user_data['role'] == 'applicant') {
                    $profile_data = array(
                        'user_id' => $user_id,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    $this->model_pelamar->tambah_profil($profile_data);
                }

                // Show success message
                $this->session->set_flashdata('success', 'Pengguna baru berhasil ditambahkan.');
                redirect('admin/pengguna');
            } else {
                // If insertion fails, show error message
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
            $this->load->view('admin/users/edit', $data);
            $this->load->view('templates/admin_footer');
        } else {
            // Get form data
            $user_data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'full_name' => $this->input->post('full_name'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'role' => $this->input->post('role'),
                'status' => $this->input->post('is_active') ? 'active' : 'inactive',
                'updated_at' => date('Y-m-d H:i:s')
            );

            // Upload profile picture if provided
            if ($_FILES['profile_picture']['name']) {
                // Make sure the directory exists and is writable
                $upload_path = FCPATH . 'uploads/profile_pictures/';
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'profile_' . time();

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('profile_picture')) {
                    // Delete old profile picture if exists
                    if ($data['user']->profile_picture) {
                        $old_file = $upload_path . $data['user']->profile_picture;
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }

                    $upload_data = $this->upload->data();
                    $user_data['profile_picture'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin/edit_pengguna/' . $id);
                }
            }

            // Update user data
            $result = $this->model_pengguna->perbarui_pengguna($id, $user_data);

            if ($result) {
                // If user role changed to applicant, create applicant profile if not exists
                if ($user_data['role'] == 'applicant') {
                    $profile = $this->model_pelamar->dapatkan_profil($id);

                    if (!$profile) {
                        $profile_data = array(
                            'user_id' => $id,
                            'created_at' => date('Y-m-d H:i:s')
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
        $result = $this->model_pengguna->perbarui_pengguna($id, array('status' => 'active'));

        if ($result) {
            $this->session->set_flashdata('success', 'Pengguna berhasil diaktifkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan pengguna. Silakan coba lagi.');
        }

        redirect('admin/pengguna');
    }

    // Nonaktifkan Pengguna
    public function nonaktifkan_pengguna($id) {
        $result = $this->model_pengguna->perbarui_pengguna($id, array('status' => 'inactive'));

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
        if (!$data['user'] || $data['user']->role != 'applicant') {
            show_404();
        }

        // Get applicant profile
        $data['profile'] = $this->model_pelamar->dapatkan_profil($id);

        // Load views
        $data['title'] = 'Profil Pelamar';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/users/applicant_profile', $data);
        $this->load->view('templates/admin_footer');
    }

    // Lihat Lamaran Pelamar
    public function lamaran_pelamar($id) {
        // Get user details
        $data['user'] = $this->model_pengguna->dapatkan_pengguna($id);

        // If user not found or not an applicant, show 404
        if (!$data['user'] || $data['user']->role != 'applicant') {
            show_404();
        }

        // Get applicant applications
        $data['applications'] = $this->model_lamaran->dapatkan_lamaran_pelamar($id);

        // Load views
        $data['title'] = 'Lamaran Pelamar';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/users/applicant_applications', $data);
        $this->load->view('templates/admin_footer');
    }

    // Blog Management
    public function blog() {
        // Get search and filter parameters
        $search = $this->input->get('search');
        $category = $this->input->get('category');
        $status = $this->input->get('status');

        // Set up pagination
        $config['base_url'] = base_url('admin/blog');
        $config['total_rows'] = $this->model_blog->hitung_artikel();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';

        // Pagination styling
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'Pertama';
        $config['last_link'] = 'Terakhir';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        // Initialize pagination
        $this->pagination->initialize($config);

        // Get posts for current page
        $page = $this->input->get('page') ? $this->input->get('page') : 0;

        // For now, get all posts (we'll implement filtering later)
        $data['posts'] = $this->model_blog->dapatkan_artikel_semua();

        // Get blog categories
        $data['categories'] = $this->model_kategori->dapatkan_kategori_blog();

        // Load views
        $data['title'] = 'Manajemen Blog';
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/blog/index', $data);
        $this->load->view('templates/admin_footer');
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

                $config['upload_path'] = './uploads/blog_images/';
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
                'updated_at' => date('Y-m-d H:i:s')
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

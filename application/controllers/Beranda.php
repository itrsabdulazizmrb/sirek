<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('model_lowongan');
        $this->load->model('model_blog');
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('email');
    }

    public function index() {
        // Get featured jobs
        $data['featured_jobs'] = $this->model_lowongan->dapatkan_lowongan_unggulan(6);

        // Get latest blog posts
        $data['latest_posts'] = $this->model_blog->dapatkan_artikel_terbaru(3);

        // Load views
        $data['title'] = 'Beranda';
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/beranda', $data);
        $this->load->view('templates/public_footer');
    }

    public function lowongan() {
        // Get filter parameters
        $filters = [
            'category' => $this->input->get('category'),
            'location' => $this->input->get('location'),
            'job_type' => $this->input->get('job_type'),
            'search' => $this->input->get('q'),
            'sort' => $this->input->get('sort') ?: 'newest'
        ];

        // Get pagination config
        $config['base_url'] = base_url('lowongan');
        $config['total_rows'] = $this->model_lowongan->hitung_lowongan_aktif($filters);
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;

        // Add query string for filters
        $query_string = '';
        if (!empty($filters['category'])) $query_string .= '&category=' . $filters['category'];
        if (!empty($filters['location'])) $query_string .= '&location=' . $filters['location'];
        if (!empty($filters['job_type'])) $query_string .= '&job_type=' . $filters['job_type'];
        if (!empty($filters['search'])) $query_string .= '&q=' . $filters['search'];
        if (!empty($filters['sort']) && $filters['sort'] != 'newest') $query_string .= '&sort=' . $filters['sort'];

        if ($query_string) {
            $config['suffix'] = '?' . ltrim($query_string, '&');
            $config['first_url'] = $config['base_url'] . '?' . ltrim($query_string, '&');
        }

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

        // Get jobs for current page
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data['jobs'] = $this->model_lowongan->dapatkan_lowongan_aktif($config['per_page'], $page, $filters);

        // Get job categories for filter
        $this->load->model('model_kategori');
        $data['categories'] = $this->model_kategori->dapatkan_kategori_lowongan();

        // Get unique locations for filter
        $data['locations'] = $this->model_lowongan->dapatkan_lokasi_unik();

        // Load views
        $data['title'] = 'Daftar Lowongan';
        $data['pagination'] = $this->pagination->create_links();
        $data['total_jobs'] = $config['total_rows']; // Menambahkan total_jobs ke data
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/lowongan', $data);
        $this->load->view('templates/public_footer');
    }

    public function detail_lowongan($id) {
        // Get job details
        $data['job'] = $this->model_lowongan->dapatkan_lowongan($id);

        // If job not found, show 404
        if (!$data['job']) {
            show_404();
        }

        // Get related jobs
        $data['related_jobs'] = $this->model_lowongan->dapatkan_lowongan_terkait($id, $data['job']->id_kategori, 4);

        // Load views
        $data['title'] = $data['job']->judul;
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/detail_lowongan', $data);
        $this->load->view('templates/public_footer');
    }

    public function blog() {
        // Get pagination config
        $config['base_url'] = base_url('blog');
        $config['total_rows'] = $this->model_blog->hitung_artikel_terpublikasi();
        $config['per_page'] = 6;
        $config['uri_segment'] = 3;

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
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['posts'] = $this->model_blog->dapatkan_artikel_terpublikasi($config['per_page'], $page);

        // Get blog categories for sidebar
        $this->load->model('model_kategori');
        $data['categories'] = $this->model_kategori->dapatkan_kategori_blog();

        // Get recent posts for sidebar
        $data['recent_posts'] = $this->model_blog->dapatkan_artikel_terbaru(5);

        // Load views
        $data['title'] = 'Blog';
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/blog', $data);
        $this->load->view('templates/public_footer');
    }

    public function artikel($slug) {
        // Get post details
        $data['post'] = $this->model_blog->dapatkan_artikel_dari_slug($slug);

        // If post not found or not published, show 404
        if (!$data['post'] || $data['post']->status != 'dipublikasi') {
            show_404();
        }

        // Increment post views
        $this->model_blog->tambah_dilihat($data['post']->id);

        // Refresh post data to get updated views count
        $data['post'] = $this->model_blog->dapatkan_artikel_dari_slug($slug);

        // Get post categories
        $data['post_categories'] = $this->model_blog->dapatkan_kategori_artikel($data['post']->id);

        // Get related posts
        $data['related_posts'] = $this->model_blog->dapatkan_artikel_terkait($data['post']->id, 3);

        // Load category model for sidebar
        $this->load->model('model_kategori');
        $data['categories'] = $this->model_kategori->dapatkan_kategori_blog();

        // Get recent posts for sidebar
        $data['recent_posts'] = $this->model_blog->dapatkan_artikel_terbaru(5);

        // Load views
        $data['title'] = $data['post']->judul;
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/artikel', $data);
        $this->load->view('templates/public_footer');
    }

    public function tentang() {
        // Load views
        $data['title'] = 'Tentang Kami';
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/tentang', $data);
        $this->load->view('templates/public_footer');
    }

    public function kontak() {
        // Form validation rules
        $this->form_validation->set_rules('name', 'Nama', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', 'Subjek', 'trim|required');
        $this->form_validation->set_rules('message', 'Pesan', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show contact form with errors
            $data['title'] = 'Hubungi Kami';
            $this->load->view('templates/public_header', $data);
            $this->load->view('public/kontak');
            $this->load->view('templates/public_footer');
        } else {
            // Get form data
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $subject = $this->input->post('subject');
            $message = $this->input->post('message');

            // Send email
            $this->email->from($email, $name);
            $this->email->to('contact@sirek.com');
            $this->email->subject($subject);
            $this->email->message($message);

            if ($this->email->send()) {
                $this->session->set_flashdata('success', 'Pesan Anda berhasil dikirim.');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengirim pesan. Silakan coba lagi.');
            }

            redirect('kontak');
        }
    }

    public function cari() {
        // Get search query
        $query = $this->input->get('q');

        if (!$query) {
            redirect('lowongan');
        }

        // Search jobs
        $data['jobs'] = $this->model_lowongan->cari_lowongan($query);

        // Load views
        $data['title'] = 'Hasil Pencarian: ' . $query;
        $data['search_query'] = $query;
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/hasil_pencarian', $data);
        $this->load->view('templates/public_footer');
    }
}

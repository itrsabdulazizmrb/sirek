<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('job_model');
        $this->load->model('blog_model');
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('email');
    }

    public function index() {
        // Get featured jobs
        $data['featured_jobs'] = $this->job_model->get_featured_jobs(6);

        // Get latest blog posts
        $data['latest_posts'] = $this->blog_model->get_latest_posts(3);

        // Load views
        $data['title'] = 'Home';
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/home', $data);
        $this->load->view('templates/public_footer');
    }

    public function jobs() {
        // Get pagination config
        $config['base_url'] = base_url('home/jobs');
        $config['total_rows'] = $this->job_model->count_active_jobs();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;

        // Pagination styling
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
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
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['jobs'] = $this->job_model->get_active_jobs($config['per_page'], $page);

        // Get job categories for filter
        $this->load->model('category_model');
        $data['categories'] = $this->category_model->get_job_categories();

        // Load views
        $data['title'] = 'Daftar Lowongan';
        $data['pagination'] = $this->pagination->create_links();
        $data['total_jobs'] = $config['total_rows']; // Menambahkan total_jobs ke data
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/jobs', $data);
        $this->load->view('templates/public_footer');
    }

    public function job_details($id) {
        // Get job details
        $data['job'] = $this->job_model->get_job($id);

        // If job not found or not active, show 404
        if (!$data['job'] || $data['job']->status != 'active') {
            show_404();
        }

        // Get related jobs
        $data['related_jobs'] = $this->job_model->get_related_jobs($id, $data['job']->category_id, 4);

        // Load views
        $data['title'] = $data['job']->title;
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/job_details', $data);
        $this->load->view('templates/public_footer');
    }

    public function blog() {
        // Get pagination config
        $config['base_url'] = base_url('home/blog');
        $config['total_rows'] = $this->blog_model->count_published_posts();
        $config['per_page'] = 6;
        $config['uri_segment'] = 3;

        // Pagination styling
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
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
        $data['posts'] = $this->blog_model->get_published_posts($config['per_page'], $page);

        // Get blog categories for sidebar
        $this->load->model('category_model');
        $data['categories'] = $this->category_model->get_blog_categories();

        // Load views
        $data['title'] = 'Blog';
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/blog', $data);
        $this->load->view('templates/public_footer');
    }

    public function blog_post($slug) {
        // Get post details
        $data['post'] = $this->blog_model->get_post_by_slug($slug);

        // If post not found or not published, show 404
        if (!$data['post'] || $data['post']->status != 'published') {
            show_404();
        }

        // Increment post views
        $this->blog_model->increment_views($data['post']->id);

        // Refresh post data to get updated views count
        $data['post'] = $this->blog_model->get_post_by_slug($slug);

        // Get post categories
        $data['post_categories'] = $this->blog_model->get_post_categories($data['post']->id);

        // Get related posts
        $data['related_posts'] = $this->blog_model->get_related_posts($data['post']->id, 3);

        // Load category model for sidebar
        $this->load->model('category_model');
        $data['categories'] = $this->category_model->get_blog_categories();

        // Load views
        $data['title'] = $data['post']->title;
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/blog_post', $data);
        $this->load->view('templates/public_footer');
    }

    public function about() {
        // Load views
        $data['title'] = 'About Us';
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/about');
        $this->load->view('templates/public_footer');
    }

    public function contact() {
        // Form validation rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show contact form with errors
            $data['title'] = 'Contact Us';
            $this->load->view('templates/public_header', $data);
            $this->load->view('public/contact');
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
                $this->session->set_flashdata('success', 'Your message has been sent successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to send message. Please try again.');
            }

            redirect('home/contact');
        }
    }

    public function search() {
        // Get search query
        $query = $this->input->get('q');

        if (!$query) {
            redirect('home/jobs');
        }

        // Search jobs
        $data['jobs'] = $this->job_model->search_jobs($query);

        // Load views
        $data['title'] = 'Search Results: ' . $query;
        $data['search_query'] = $query;
        $this->load->view('templates/public_header', $data);
        $this->load->view('public/search_results', $data);
        $this->load->view('templates/public_footer');
    }
}

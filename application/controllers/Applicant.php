<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Check if user is logged in and is an applicant
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'applicant') {
            redirect('auth');
        }

        // Load models
        $this->load->model('user_model');
        $this->load->model('applicant_model');
        $this->load->model('job_model');
        $this->load->model('application_model');
        $this->load->model('assessment_model');

        // Load libraries
        $this->load->library('upload');
    }

    public function index() {
        redirect('applicant/dashboard');
    }

    public function dashboard() {
        // Get applicant's applications
        $user_id = $this->session->userdata('user_id');
        $data['applications'] = $this->application_model->get_applicant_applications($user_id);

        // Get recommended jobs
        $data['recommended_jobs'] = $this->job_model->get_recommended_jobs($user_id, 5);

        // Get profile completion percentage
        $data['profile_completion'] = $this->applicant_model->get_profile_completion($user_id);

        // Load views
        $data['title'] = 'Applicant Dashboard';
        $this->load->view('templates/applicant_header', $data);
        $this->load->view('applicant/dashboard', $data);
        $this->load->view('templates/applicant_footer');
    }

    public function profile() {
        // Get applicant profile
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->user_model->get_user($user_id);
        $data['profile'] = $this->applicant_model->get_profile($user_id);

        // Form validation rules
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
        $this->form_validation->set_rules('education', 'Education', 'trim|required');
        $this->form_validation->set_rules('experience', 'Experience', 'trim|required');
        $this->form_validation->set_rules('skills', 'Skills', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'My Profile';
            $this->load->view('templates/applicant_header', $data);
            $this->load->view('applicant/profile', $data);
            $this->load->view('templates/applicant_footer');
        } else {
            // Get form data
            $user_data = array(
                'full_name' => $this->input->post('full_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address')
            );

            $profile_data = array(
                'date_of_birth' => $this->input->post('date_of_birth'),
                'gender' => $this->input->post('gender'),
                'education' => $this->input->post('education'),
                'experience' => $this->input->post('experience'),
                'skills' => $this->input->post('skills'),
                'linkedin_url' => $this->input->post('linkedin_url'),
                'portfolio_url' => $this->input->post('portfolio_url')
            );

            // Handle resume upload
            if ($_FILES['resume']['name']) {
                // Make sure the directory exists and is writable
                $upload_path = FCPATH . 'uploads/resumes/';
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'pdf|doc|docx';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'resume_' . $user_id . '_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('resume')) {
                    $upload_data = $this->upload->data();
                    $profile_data['resume'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('applicant/profile');
                }
            }

            // Handle profile picture upload
            if ($_FILES['profile_picture']['name']) {
                // Make sure the directory exists and is writable
                $upload_path = FCPATH . 'uploads/profile_pictures/';
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 1024; // 1MB
                $config['file_name'] = 'profile_' . $user_id . '_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('profile_picture')) {
                    $upload_data = $this->upload->data();
                    $user_data['profile_picture'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('applicant/profile');
                }
            }

            // Update user and profile data
            $this->user_model->update_user($user_id, $user_data);
            $this->applicant_model->update_profile($user_id, $profile_data);

            // Update session data
            $this->session->set_userdata('full_name', $user_data['full_name']);
            $this->session->set_userdata('email', $user_data['email']);

            // Show success message
            $this->session->set_flashdata('success', 'Profile has been updated successfully.');
            redirect('applicant/profile');
        }
    }

    public function applications() {
        // Get applicant's applications
        $user_id = $this->session->userdata('user_id');
        $data['applications'] = $this->application_model->get_applicant_applications($user_id);

        // Load views
        $data['title'] = 'My Applications';
        $this->load->view('templates/applicant_header', $data);
        $this->load->view('applicant/applications', $data);
        $this->load->view('templates/applicant_footer');
    }

    public function apply($job_id) {
        // Get job details
        $data['job'] = $this->job_model->get_job($job_id);

        // If job not found or not active, show 404
        if (!$data['job'] || $data['job']->status != 'active') {
            show_404();
        }

        // Check if already applied
        $user_id = $this->session->userdata('user_id');
        if ($this->application_model->has_applied($user_id, $job_id)) {
            $this->session->set_flashdata('error', 'You have already applied for this job.');
            redirect('home/job_details/' . $job_id);
        }

        // Get applicant profile
        $data['profile'] = $this->applicant_model->get_profile($user_id);

        // Form validation rules
        $this->form_validation->set_rules('cover_letter', 'Cover Letter', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Apply for Job';
            $this->load->view('templates/applicant_header', $data);
            $this->load->view('applicant/apply', $data);
            $this->load->view('templates/applicant_footer');
        } else {
            // Get form data
            $application_data = array(
                'job_id' => $job_id,
                'applicant_id' => $user_id,
                'cover_letter' => $this->input->post('cover_letter')
            );

            // Handle resume upload
            if ($_FILES['resume']['name']) {
                // Make sure the directory exists and is writable
                $upload_path = FCPATH . 'uploads/resumes/';
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'pdf|doc|docx';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'resume_' . $user_id . '_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('resume')) {
                    $upload_data = $this->upload->data();
                    $application_data['resume'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('applicant/apply/' . $job_id);
                }
            } else if ($data['profile']->resume) {
                // Use existing resume from profile
                $application_data['resume'] = $data['profile']->resume;
            } else {
                $this->session->set_flashdata('error', 'Please upload your resume.');
                redirect('applicant/apply/' . $job_id);
            }

            // Insert application data
            $application_id = $this->application_model->insert_application($application_data);

            if ($application_id) {
                // Show success message
                $this->session->set_flashdata('success', 'Your application has been submitted successfully.');
                redirect('applicant/applications');
            } else {
                // If insertion fails, show error message
                $this->session->set_flashdata('error', 'Failed to submit application. Please try again.');
                redirect('applicant/apply/' . $job_id);
            }
        }
    }

    public function application_details($id) {
        // Get application details
        $user_id = $this->session->userdata('user_id');
        $data['application'] = $this->application_model->get_application($id);

        // If application not found or not owned by current user, show 404
        if (!$data['application'] || $data['application']->applicant_id != $user_id) {
            show_404();
        }

        // Get job details
        $data['job'] = $this->job_model->get_job($data['application']->job_id);

        // Get assessment results
        $data['assessments'] = $this->assessment_model->get_applicant_assessments($id);

        // Load views
        $data['title'] = 'Application Details';
        $this->load->view('templates/applicant_header', $data);
        $this->load->view('applicant/application_details', $data);
        $this->load->view('templates/applicant_footer');
    }

    public function assessments() {
        // Get applicant's assessments
        $user_id = $this->session->userdata('user_id');
        $data['assessments'] = $this->assessment_model->get_applicant_all_assessments($user_id);

        // Load views
        $data['title'] = 'My Assessments';
        $this->load->view('templates/applicant_header', $data);
        $this->load->view('applicant/assessments', $data);
        $this->load->view('templates/applicant_footer');
    }

    public function take_assessment($assessment_id, $application_id) {
        // Get assessment details
        $data['assessment'] = $this->assessment_model->get_assessment($assessment_id);

        // If assessment not found, show 404
        if (!$data['assessment']) {
            show_404();
        }

        // Get applicant assessment
        $user_id = $this->session->userdata('user_id');
        $data['applicant_assessment'] = $this->assessment_model->get_applicant_assessment($application_id, $assessment_id);

        // If applicant assessment not found or not owned by current user, show 404
        if (!$data['applicant_assessment'] || $data['applicant_assessment']->applicant_id != $user_id) {
            show_404();
        }

        // Get assessment questions
        $data['questions'] = $this->assessment_model->get_assessment_questions($assessment_id);

        // Load views
        $data['title'] = 'Take Assessment';
        $data['application_id'] = $application_id;
        $this->load->view('templates/applicant_header', $data);
        $this->load->view('applicant/take_assessment', $data);
        $this->load->view('templates/applicant_footer');
    }

    public function submit_assessment() {
        // Get form data
        $assessment_id = $this->input->post('assessment_id');
        $application_id = $this->input->post('application_id');
        $applicant_assessment_id = $this->input->post('applicant_assessment_id');

        // Update applicant assessment status
        $this->assessment_model->update_applicant_assessment_status($applicant_assessment_id, 'completed');

        // Process answers
        $questions = $this->assessment_model->get_assessment_questions($assessment_id);

        foreach ($questions as $question) {
            $answer_data = array(
                'applicant_assessment_id' => $applicant_assessment_id,
                'question_id' => $question->id
            );

            if ($question->question_type == 'multiple_choice') {
                $answer_data['selected_option_id'] = $this->input->post('question_' . $question->id);
            } else if ($question->question_type == 'true_false') {
                $answer_data['answer_text'] = $this->input->post('question_' . $question->id);
            } else if ($question->question_type == 'essay') {
                $answer_data['answer_text'] = $this->input->post('question_' . $question->id);
            } else if ($question->question_type == 'file_upload') {
                // Handle file upload
                // Make sure the directory exists and is writable
                $upload_path = FCPATH . 'uploads/answers/';
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'pdf|doc|docx|jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'answer_' . $applicant_assessment_id . '_' . $question->id . '_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('question_' . $question->id)) {
                    $upload_data = $this->upload->data();
                    $answer_data['file_upload'] = $upload_data['file_name'];
                }
            }

            // Insert answer
            $this->assessment_model->insert_applicant_answer($answer_data);
        }

        // Show success message
        $this->session->set_flashdata('success', 'Assessment has been submitted successfully.');
        redirect('applicant/application_details/' . $application_id);
    }

    public function change_password() {
        // Form validation rules
        $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[new_password]');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show form with errors
            $data['title'] = 'Change Password';
            $this->load->view('templates/applicant_header', $data);
            $this->load->view('applicant/change_password');
            $this->load->view('templates/applicant_footer');
        } else {
            // Get form data
            $user_id = $this->session->userdata('user_id');
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');

            // Check if current password is correct
            $user = $this->user_model->get_user($user_id);

            if (password_verify($current_password, $user->password)) {
                // Update password
                $this->user_model->update_password($user_id, password_hash($new_password, PASSWORD_DEFAULT));

                // Show success message
                $this->session->set_flashdata('success', 'Password has been changed successfully.');
                redirect('applicant/dashboard');
            } else {
                // If current password is incorrect, show error message
                $this->session->set_flashdata('error', 'Current password is incorrect.');
                redirect('applicant/change_password');
            }
        }
    }
}

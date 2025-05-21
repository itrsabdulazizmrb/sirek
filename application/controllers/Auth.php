<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index() {
        // If user is already logged in, redirect to appropriate dashboard
        if ($this->session->userdata('logged_in')) {
            redirect($this->_get_dashboard_url());
        }
        
        // Show login page
        $data['title'] = 'Login';
        $this->load->view('templates/auth_header', $data);
        $this->load->view('auth/login');
        $this->load->view('templates/auth_footer');
    }

    public function login() {
        // If user is already logged in, redirect to appropriate dashboard
        if ($this->session->userdata('logged_in')) {
            redirect($this->_get_dashboard_url());
        }
        
        // Form validation rules
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show login form with errors
            $data['title'] = 'Login';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            // Get form data
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            // Check if user exists
            $user = $this->user_model->get_user_by_username($username);
            
            if ($user && password_verify($password, $user->password)) {
                // Set user session data
                $session_data = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'full_name' => $user->full_name,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($session_data);
                
                // Update last login time
                $this->user_model->update_last_login($user->id);
                
                // Redirect to appropriate dashboard
                redirect($this->_get_dashboard_url());
            } else {
                // If login fails, show error message
                $this->session->set_flashdata('error', 'Invalid username or password');
                redirect('auth');
            }
        }
    }

    public function register() {
        // If user is already logged in, redirect to appropriate dashboard
        if ($this->session->userdata('logged_in')) {
            redirect($this->_get_dashboard_url());
        }
        
        // Form validation rules
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
        
        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show registration form with errors
            $data['title'] = 'Register';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/register');
            $this->load->view('templates/auth_footer');
        } else {
            // Get form data
            $data = array(
                'full_name' => $this->input->post('full_name'),
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role' => 'applicant', // Default role for new registrations
                'status' => 'active'
            );
            
            // Insert user data
            $user_id = $this->user_model->insert_user($data);
            
            if ($user_id) {
                // Create empty applicant profile
                $this->load->model('applicant_model');
                $this->applicant_model->create_profile($user_id);
                
                // Show success message and redirect to login page
                $this->session->set_flashdata('success', 'Registration successful. You can now login.');
                redirect('auth');
            } else {
                // If registration fails, show error message
                $this->session->set_flashdata('error', 'Registration failed. Please try again.');
                redirect('auth/register');
            }
        }
    }

    public function logout() {
        // Destroy session and redirect to login page
        $this->session->sess_destroy();
        redirect('auth');
    }

    public function forgot_password() {
        // Form validation rules
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        
        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show forgot password form with errors
            $data['title'] = 'Forgot Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/forgot_password');
            $this->load->view('templates/auth_footer');
        } else {
            // Get email from form
            $email = $this->input->post('email');
            
            // Check if user with this email exists
            $user = $this->user_model->get_user_by_email($email);
            
            if ($user) {
                // Generate reset token
                $token = bin2hex(random_bytes(32));
                $this->user_model->save_reset_token($user->id, $token);
                
                // Send reset email
                $reset_link = base_url('auth/reset_password/' . $token);
                $message = "Hello " . $user->full_name . ",\n\n";
                $message .= "You have requested to reset your password. Please click the link below to reset your password:\n\n";
                $message .= $reset_link . "\n\n";
                $message .= "If you did not request this, please ignore this email.\n\n";
                $message .= "Regards,\nSIREK Team";
                
                $this->email->from('noreply@sirek.com', 'SIREK');
                $this->email->to($email);
                $this->email->subject('Password Reset Request');
                $this->email->message($message);
                
                if ($this->email->send()) {
                    $this->session->set_flashdata('success', 'Password reset link has been sent to your email.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to send reset email. Please try again.');
                }
            } else {
                // Don't reveal that the email doesn't exist for security reasons
                $this->session->set_flashdata('success', 'If your email exists in our system, a password reset link has been sent.');
            }
            
            redirect('auth/forgot_password');
        }
    }

    public function reset_password($token = NULL) {
        if (!$token) {
            show_404();
        }
        
        // Check if token is valid
        $user = $this->user_model->get_user_by_reset_token($token);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'Invalid or expired reset token.');
            redirect('auth');
        }
        
        // Form validation rules
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
        
        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show reset password form with errors
            $data['title'] = 'Reset Password';
            $data['token'] = $token;
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/reset_password', $data);
            $this->load->view('templates/auth_footer');
        } else {
            // Get new password from form
            $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            
            // Update user password and clear reset token
            $this->user_model->update_password($user->id, $password);
            
            // Show success message and redirect to login page
            $this->session->set_flashdata('success', 'Password has been reset successfully. You can now login with your new password.');
            redirect('auth');
        }
    }

    // Helper method to get dashboard URL based on user role
    private function _get_dashboard_url() {
        $role = $this->session->userdata('role');
        
        switch ($role) {
            case 'admin':
                return 'admin/dashboard';
            case 'staff':
                return 'admin/dashboard';
            case 'applicant':
                return 'applicant/dashboard';
            default:
                return 'auth/logout';
        }
    }
}

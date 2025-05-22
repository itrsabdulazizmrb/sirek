<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('model_pengguna');
        $this->load->model('model_pelamar');
        $this->load->library('form_validation');
        $this->load->library('email');
    }

    public function index() {
        // Redirect ke halaman login
        redirect('auth/login');
    }

    public function login() {
        // Cek apakah user sudah login
        if ($this->session->userdata('logged_in')) {
            // Redirect berdasarkan role
            if ($this->session->userdata('role') == 'admin') {
                redirect('admin');
            } else if ($this->session->userdata('role') == 'applicant') {
                redirect('pelamar');
            } else {
                redirect('beranda');
            }
        }

        // Set aturan validasi
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan form login
            $data['title'] = 'Login';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            // Jika validasi berhasil, proses login
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Verifikasi login
            $user = $this->model_pengguna->verifikasi_login($username, $password);

            if ($user) {
                // Cek status user
                if ($user->status == 'active') {
                    // Buat data session
                    $session_data = array(
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'full_name' => $user->full_name,
                        'role' => $user->role,
                        'profile_picture' => $user->profile_picture,
                        'logged_in' => TRUE
                    );

                    // Set session
                    $this->session->set_userdata($session_data);

                    // Update last login
                    $this->model_pengguna->perbarui_pengguna($user->id, array('last_login' => date('Y-m-d H:i:s')));

                    // Redirect berdasarkan role
                    if ($user->role == 'admin') {
                        redirect('admin');
                    } else if ($user->role == 'applicant') {
                        redirect('pelamar');
                    } else {
                        redirect('beranda');
                    }
                } else {
                    // Jika user tidak aktif
                    $this->session->set_flashdata('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
                    redirect('auth/login');
                }
            } else {
                // Jika login gagal
                $this->session->set_flashdata('error', 'Username atau password salah.');
                redirect('auth/login');
            }
        }
    }

    public function daftar() {
        // Cek apakah user sudah login
        if ($this->session->userdata('logged_in')) {
            // Redirect berdasarkan role
            if ($this->session->userdata('role') == 'admin') {
                redirect('admin');
            } else if ($this->session->userdata('role') == 'applicant') {
                redirect('pelamar');
            } else {
                redirect('beranda');
            }
        }

        // Set aturan validasi
        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'trim|required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan form registrasi
            $data['title'] = 'Daftar';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/daftar');
            $this->load->view('templates/auth_footer');
        } else {
            // Jika validasi berhasil, proses registrasi
            $data = array(
                'full_name' => $this->input->post('full_name'),
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role' => 'applicant',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            );

            // Simpan data user
            $user_id = $this->model_pengguna->tambah_pengguna($data);

            if ($user_id) {
                // Buat profil pelamar kosong
                $this->model_pelamar->buat_profil($user_id);

                // Tampilkan pesan sukses
                $this->session->set_flashdata('success', 'Pendaftaran berhasil. Silakan login.');
                redirect('auth/login');
            } else {
                // Jika gagal menyimpan
                $this->session->set_flashdata('error', 'Gagal mendaftar. Silakan coba lagi.');
                redirect('auth/daftar');
            }
        }
    }

    public function lupa_password() {
        // Set aturan validasi
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan form lupa password
            $data['title'] = 'Lupa Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/lupa_password');
            $this->load->view('templates/auth_footer');
        } else {
            // Jika validasi berhasil, proses reset password
            $email = $this->input->post('email');
            $user = $this->model_pengguna->dapatkan_pengguna_dari_email($email);

            if ($user) {
                // Generate token reset password
                $token = bin2hex(random_bytes(32));
                $token_data = array(
                    'email' => $email,
                    'token' => $token,
                    'created_at' => date('Y-m-d H:i:s')
                );

                // Simpan token ke database
                $this->db->insert('password_resets', $token_data);

                // Kirim email reset password
                $this->email->from('noreply@sirek.com', 'SIREK');
                $this->email->to($email);
                $this->email->subject('Reset Password');
                $this->email->message('Klik link berikut untuk reset password Anda: ' . base_url('auth/reset_password/' . $token));

                if ($this->email->send()) {
                    $this->session->set_flashdata('success', 'Link reset password telah dikirim ke email Anda.');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengirim email reset password. Silakan coba lagi.');
                }
            } else {
                $this->session->set_flashdata('error', 'Email tidak ditemukan.');
            }

            redirect('auth/lupa_password');
        }
    }

    public function reset_password($token) {
        // Cek apakah token valid
        $this->db->where('token', $token);
        $query = $this->db->get('password_resets');
        $reset = $query->row();

        if (!$reset) {
            $this->session->set_flashdata('error', 'Token reset password tidak valid.');
            redirect('auth/login');
        }

        // Cek apakah token masih berlaku (24 jam)
        $token_created = new DateTime($reset->created_at);
        $now = new DateTime();
        $interval = $token_created->diff($now);
        $hours = $interval->h + ($interval->days * 24);

        if ($hours > 24) {
            $this->session->set_flashdata('error', 'Token reset password sudah kadaluarsa.');
            redirect('auth/login');
        }

        // Set aturan validasi
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'trim|required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan form reset password
            $data['title'] = 'Reset Password';
            $data['token'] = $token;
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/reset_password', $data);
            $this->load->view('templates/auth_footer');
        } else {
            // Jika validasi berhasil, proses reset password
            $email = $reset->email;
            $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

            // Update password user
            $this->db->where('email', $email);
            $this->db->update('users', array('password' => $password));

            // Hapus token reset password
            $this->db->where('token', $token);
            $this->db->delete('password_resets');

            // Tampilkan pesan sukses
            $this->session->set_flashdata('success', 'Password berhasil direset. Silakan login.');
            redirect('auth/login');
        }
    }

    public function logout() {
        // Hapus semua data session
        $this->session->sess_destroy();

        // Redirect ke halaman login
        redirect('auth/login');
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Pengguna extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Dapatkan semua pengguna
    public function dapatkan_pengguna_semua() {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('users');
        return $query->result();
    }

    // Dapatkan pengguna berdasarkan ID
    public function dapatkan_pengguna($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Dapatkan pengguna berdasarkan username
    public function dapatkan_pengguna_dari_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Dapatkan pengguna berdasarkan email
    public function dapatkan_pengguna_dari_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Tambah pengguna baru
    public function tambah_pengguna($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    // Perbarui data pengguna
    public function perbarui_pengguna($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    // Hapus pengguna
    public function hapus_pengguna($id) {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    // Perbarui password pengguna
    public function perbarui_password($id, $password) {
        $this->db->where('id', $id);
        return $this->db->update('users', array('password' => $password));
    }

    // Hitung total pengguna
    public function hitung_pengguna() {
        return $this->db->count_all('users');
    }

    // Hitung pengguna berdasarkan role
    public function hitung_pengguna_berdasarkan_role($role) {
        $this->db->where('role', $role);
        $query = $this->db->get('users');
        return $query->num_rows();
    }

    // Hitung total pelamar
    public function hitung_pelamar() {
        $this->db->where('role', 'applicant');
        $query = $this->db->get('users');
        return $query->num_rows();
    }

    // Dapatkan pengguna dengan paginasi
    public function dapatkan_pengguna_paginasi($limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('users');
        return $query->result();
    }

    // Cari pengguna
    public function cari_pengguna($keyword) {
        $this->db->like('username', $keyword);
        $this->db->or_like('email', $keyword);
        $this->db->or_like('full_name', $keyword);
        $query = $this->db->get('users');
        return $query->result();
    }

    // Filter pengguna berdasarkan role
    public function filter_pengguna_berdasarkan_role($role) {
        $this->db->where('role', $role);
        $query = $this->db->get('users');
        return $query->result();
    }

    // Filter pengguna berdasarkan status
    public function filter_pengguna_berdasarkan_status($status) {
        $this->db->where('status', $status);
        $query = $this->db->get('users');
        return $query->result();
    }

    // Dapatkan statistik pengguna berdasarkan bulan
    public function dapatkan_statistik_pengguna_bulanan($year) {
        $this->db->select('MONTH(created_at) as month, COUNT(*) as count');
        $this->db->from('users');
        $this->db->where('YEAR(created_at)', $year);
        $this->db->group_by('MONTH(created_at)');
        $query = $this->db->get();
        return $query->result();
    }

    // Verifikasi login
    public function verifikasi_login($username, $password) {
        // Cek apakah username ada
        $this->db->where('username', $username);
        $this->db->or_where('email', $username);
        $query = $this->db->get('users');
        $user = $query->row();

        // Jika user ditemukan, verifikasi password
        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }

        return false;
    }
}

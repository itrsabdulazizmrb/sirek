<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Lamaran extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Dapatkan semua lamaran
    public function dapatkan_lamaran_semua() {
        $this->db->select('lamaran_pekerjaan.*, lowongan_pekerjaan.judul as job_title, lowongan_pekerjaan.batas_waktu as deadline, pengguna.nama_lengkap as applicant_name, pengguna.email as applicant_email, pengguna.foto_profil as profile_picture');
        $this->db->from('lamaran_pekerjaan');
        $this->db->join('lowongan_pekerjaan', 'lowongan_pekerjaan.id = lamaran_pekerjaan.id_pekerjaan', 'left');
        $this->db->join('pengguna', 'pengguna.id = lamaran_pekerjaan.id_pelamar', 'left');
        $this->db->order_by('lamaran_pekerjaan.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan lamaran berdasarkan ID
    public function dapatkan_lamaran($id) {
        // Check if catatan_admin column exists
        if (!$this->db->field_exists('catatan_admin', 'lamaran_pekerjaan')) {
            // Add catatan_admin column if it doesn't exist
            $this->db->query('ALTER TABLE lamaran_pekerjaan ADD COLUMN catatan_admin TEXT NULL');
        }

        $this->db->select('lamaran_pekerjaan.*, lowongan_pekerjaan.judul as job_title, lowongan_pekerjaan.batas_waktu as deadline, pengguna.nama_lengkap as applicant_name, pengguna.email as applicant_email, pengguna.foto_profil as profile_picture');
        $this->db->from('lamaran_pekerjaan');
        $this->db->join('lowongan_pekerjaan', 'lowongan_pekerjaan.id = lamaran_pekerjaan.id_pekerjaan', 'left');
        $this->db->join('pengguna', 'pengguna.id = lamaran_pekerjaan.id_pelamar', 'left');
        $this->db->where('lamaran_pekerjaan.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Dapatkan lamaran berdasarkan pelamar
    public function dapatkan_lamaran_pelamar($user_id) {
        $this->db->select('lamaran_pekerjaan.*, lowongan_pekerjaan.judul as job_title, lowongan_pekerjaan.batas_waktu as deadline, lowongan_pekerjaan.lokasi as location, lowongan_pekerjaan.jenis_pekerjaan as job_type');
        $this->db->from('lamaran_pekerjaan');
        $this->db->join('lowongan_pekerjaan', 'lowongan_pekerjaan.id = lamaran_pekerjaan.id_pekerjaan', 'left');
        $this->db->where('lamaran_pekerjaan.id_pelamar', $user_id);
        $this->db->order_by('lamaran_pekerjaan.tanggal_lamaran', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan lamaran berdasarkan lowongan
    public function dapatkan_lamaran_lowongan($job_id) {
        $this->db->select('lamaran_pekerjaan.*, lowongan_pekerjaan.judul as job_title, pengguna.nama_lengkap as applicant_name, pengguna.email as applicant_email, pengguna.foto_profil as profile_picture');
        $this->db->from('lamaran_pekerjaan');
        $this->db->join('pengguna', 'pengguna.id = lamaran_pekerjaan.id_pelamar', 'left');
        $this->db->join('lowongan_pekerjaan', 'lowongan_pekerjaan.id = lamaran_pekerjaan.id_pekerjaan', 'left');
        $this->db->where('lamaran_pekerjaan.id_pekerjaan', $job_id);
        $this->db->order_by('lamaran_pekerjaan.tanggal_lamaran', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Tambah lamaran baru
    public function tambah_lamaran($data) {
        $data['tanggal_lamaran'] = date('Y-m-d H:i:s');
        $data['status'] = 'menunggu';
        $this->db->insert('lamaran_pekerjaan', $data);
        return $this->db->insert_id();
    }

    // Perbarui status lamaran
    public function perbarui_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('lamaran_pekerjaan', array('status' => $status));
    }

    // Perbarui lamaran
    public function perbarui_lamaran($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('lamaran_pekerjaan', $data);
    }

    // Tambah catatan admin
    public function tambah_catatan_admin($id, $note) {
        // Check if catatan_admin column exists
        if (!$this->db->field_exists('catatan_admin', 'lamaran_pekerjaan')) {
            // Add catatan_admin column if it doesn't exist
            $this->db->query('ALTER TABLE lamaran_pekerjaan ADD COLUMN catatan_admin TEXT NULL');
        }

        $this->db->where('id', $id);
        return $this->db->update('lamaran_pekerjaan', array('catatan_admin' => $note));
    }

    // Hapus lamaran
    public function hapus_lamaran($id) {
        $this->db->where('id', $id);
        return $this->db->delete('lamaran_pekerjaan');
    }

    // Cek apakah pelamar sudah melamar untuk lowongan tertentu
    public function sudah_melamar($user_id, $job_id) {
        $this->db->where('id_pelamar', $user_id);
        $this->db->where('id_pekerjaan', $job_id);
        $query = $this->db->get('lamaran_pekerjaan');
        return ($query->num_rows() > 0);
    }

    // Hitung total lamaran
    public function hitung_lamaran() {
        return $this->db->count_all('lamaran_pekerjaan');
    }

    // Hitung lamaran baru hari ini
    public function hitung_lamaran_baru() {
        $this->db->where('DATE(tanggal_lamaran)', date('Y-m-d'));
        $query = $this->db->get('lamaran_pekerjaan');
        return $query->num_rows();
    }

    // Dapatkan lamaran terbaru
    public function dapatkan_lamaran_terbaru($limit) {
        $this->db->select('lamaran_pekerjaan.*, lowongan_pekerjaan.judul as job_title, lowongan_pekerjaan.batas_waktu as deadline, pengguna.nama_lengkap as applicant_name, pengguna.foto_profil as profile_picture');
        $this->db->from('lamaran_pekerjaan');
        $this->db->join('lowongan_pekerjaan', 'lowongan_pekerjaan.id = lamaran_pekerjaan.id_pekerjaan', 'left');
        $this->db->join('pengguna', 'pengguna.id = lamaran_pekerjaan.id_pelamar', 'left');
        $this->db->order_by('lamaran_pekerjaan.tanggal_lamaran', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan statistik lamaran berdasarkan bulan
    public function dapatkan_statistik_lamaran_bulanan($year) {
        $this->db->select('MONTH(tanggal_lamaran) as month, COUNT(*) as count');
        $this->db->from('lamaran_pekerjaan');
        $this->db->where('YEAR(tanggal_lamaran)', $year);
        $this->db->group_by('MONTH(tanggal_lamaran)');
        $query = $this->db->get();
        return $query->result();
    }

    // Hitung lamaran berdasarkan lowongan
    public function hitung_lamaran_berdasarkan_lowongan($job_id) {
        $this->db->where('id_pekerjaan', $job_id);
        $query = $this->db->get('lamaran_pekerjaan');
        return $query->num_rows();
    }

    // Dapatkan lamaran dengan paginasi
    public function dapatkan_lamaran_paginasi($limit, $start) {
        $this->db->select('lamaran_pekerjaan.*, lowongan_pekerjaan.judul as job_title, lowongan_pekerjaan.batas_waktu as deadline, pengguna.nama_lengkap as applicant_name, pengguna.email as applicant_email, pengguna.foto_profil as profile_picture');
        $this->db->from('lamaran_pekerjaan');
        $this->db->join('lowongan_pekerjaan', 'lowongan_pekerjaan.id = lamaran_pekerjaan.id_pekerjaan', 'left');
        $this->db->join('pengguna', 'pengguna.id = lamaran_pekerjaan.id_pelamar', 'left');
        $this->db->order_by('lamaran_pekerjaan.id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    // Filter lamaran berdasarkan status
    public function filter_lamaran_berdasarkan_status($status) {
        $this->db->select('lamaran_pekerjaan.*, lowongan_pekerjaan.judul as job_title, lowongan_pekerjaan.batas_waktu as deadline, pengguna.nama_lengkap as applicant_name, pengguna.email as applicant_email, pengguna.foto_profil as profile_picture');
        $this->db->from('lamaran_pekerjaan');
        $this->db->join('lowongan_pekerjaan', 'lowongan_pekerjaan.id = lamaran_pekerjaan.id_pekerjaan', 'left');
        $this->db->join('pengguna', 'pengguna.id = lamaran_pekerjaan.id_pelamar', 'left');
        $this->db->where('lamaran_pekerjaan.status', $status);
        $this->db->order_by('lamaran_pekerjaan.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Hitung lamaran berdasarkan status
    public function hitung_lamaran_berdasarkan_status($status) {
        $this->db->where('status', $status);
        $query = $this->db->get('lamaran_pekerjaan');
        return $query->num_rows();
    }

    // Dapatkan jumlah lamaran per lowongan
    public function dapatkan_jumlah_lamaran_per_lowongan() {
        $this->db->select('lowongan_pekerjaan.id, lowongan_pekerjaan.judul as title, COUNT(lamaran_pekerjaan.id) as application_count');
        $this->db->from('lowongan_pekerjaan');
        $this->db->join('lamaran_pekerjaan', 'lamaran_pekerjaan.id_pekerjaan = lowongan_pekerjaan.id', 'left');
        $this->db->where('lowongan_pekerjaan.status', 'aktif');
        $this->db->group_by('lowongan_pekerjaan.id');
        $this->db->order_by('application_count', 'DESC');
        $this->db->limit(10); // Ambil 10 lowongan teratas
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan semua lamaran aktif
    public function dapatkan_semua_lamaran_aktif() {
        $this->db->select('lamaran_pekerjaan.*, lowongan_pekerjaan.judul as job_title, lowongan_pekerjaan.batas_waktu as deadline, pengguna.nama_lengkap as applicant_name, pengguna.email as applicant_email, pengguna.foto_profil as profile_picture');
        $this->db->from('lamaran_pekerjaan');
        $this->db->join('lowongan_pekerjaan', 'lowongan_pekerjaan.id = lamaran_pekerjaan.id_pekerjaan', 'left');
        $this->db->join('pengguna', 'pengguna.id = lamaran_pekerjaan.id_pelamar', 'left');
        $this->db->where_in('lamaran_pekerjaan.status', ['menunggu', 'direview', 'seleksi', 'wawancara']);
        $this->db->order_by('lamaran_pekerjaan.tanggal_lamaran', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Migrasi status lama ke status baru
    public function migrasi_status_lamaran() {
        // Array mapping status lama ke status baru
        $status_mapping = [
            'interview' => 'wawancara',
            'interviewed' => 'wawancara',
            'shortlisted' => 'seleksi',
            'offered' => 'seleksi',
            'hired' => 'diterima',
            'rejected' => 'seleksi',
            'pending' => 'menunggu',
            'reviewed' => 'direview'
        ];

        // Perbarui status lama ke status baru
        foreach ($status_mapping as $old_status => $new_status) {
            $this->db->where('status', $old_status);
            $this->db->update('lamaran_pekerjaan', ['status' => $new_status]);
        }

        return true;
    }
}

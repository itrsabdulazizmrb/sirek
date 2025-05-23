<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Lamaran extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Dapatkan semua lamaran
    public function dapatkan_lamaran_semua() {
        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.deadline, users.full_name as applicant_name, users.email as applicant_email, users.profile_picture');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->join('users', 'users.id = job_applications.applicant_id', 'left');
        $this->db->order_by('job_applications.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan lamaran berdasarkan ID
    public function dapatkan_lamaran($id) {
        // Check if admin_notes column exists
        if (!$this->db->field_exists('admin_notes', 'job_applications')) {
            // Add admin_notes column if it doesn't exist
            $this->db->query('ALTER TABLE job_applications ADD COLUMN admin_notes TEXT NULL');
        }

        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.deadline, users.full_name as applicant_name, users.email as applicant_email, users.profile_picture');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->join('users', 'users.id = job_applications.applicant_id', 'left');
        $this->db->where('job_applications.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Dapatkan lamaran berdasarkan pelamar
    public function dapatkan_lamaran_pelamar($user_id) {
        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.deadline, job_postings.location, job_postings.job_type');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->where('job_applications.applicant_id', $user_id);
        $this->db->order_by('job_applications.application_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan lamaran berdasarkan lowongan
    public function dapatkan_lamaran_lowongan($job_id) {
        $this->db->select('job_applications.*, job_postings.title as job_title, users.full_name as applicant_name, users.email as applicant_email, users.profile_picture');
        $this->db->from('job_applications');
        $this->db->join('users', 'users.id = job_applications.applicant_id', 'left');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->where('job_applications.job_id', $job_id);
        $this->db->order_by('job_applications.application_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Tambah lamaran baru
    public function tambah_lamaran($data) {
        $data['application_date'] = date('Y-m-d H:i:s');
        $data['status'] = 'pending';
        $this->db->insert('job_applications', $data);
        return $this->db->insert_id();
    }

    // Perbarui status lamaran
    public function perbarui_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('job_applications', array('status' => $status));
    }

    // Perbarui lamaran
    public function perbarui_lamaran($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('job_applications', $data);
    }

    // Tambah catatan admin
    public function tambah_catatan_admin($id, $note) {
        // Check if admin_notes column exists
        if (!$this->db->field_exists('admin_notes', 'job_applications')) {
            // Add admin_notes column if it doesn't exist
            $this->db->query('ALTER TABLE job_applications ADD COLUMN admin_notes TEXT NULL');
        }

        $this->db->where('id', $id);
        return $this->db->update('job_applications', array('admin_notes' => $note));
    }

    // Hapus lamaran
    public function hapus_lamaran($id) {
        $this->db->where('id', $id);
        return $this->db->delete('job_applications');
    }

    // Cek apakah pelamar sudah melamar untuk lowongan tertentu
    public function sudah_melamar($user_id, $job_id) {
        $this->db->where('applicant_id', $user_id);
        $this->db->where('job_id', $job_id);
        $query = $this->db->get('job_applications');
        return ($query->num_rows() > 0);
    }

    // Hitung total lamaran
    public function hitung_lamaran() {
        return $this->db->count_all('job_applications');
    }

    // Hitung lamaran baru hari ini
    public function hitung_lamaran_baru() {
        $this->db->where('DATE(application_date)', date('Y-m-d'));
        $query = $this->db->get('job_applications');
        return $query->num_rows();
    }

    // Dapatkan lamaran terbaru
    public function dapatkan_lamaran_terbaru($limit) {
        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.deadline, users.full_name as applicant_name, users.profile_picture');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->join('users', 'users.id = job_applications.applicant_id', 'left');
        $this->db->order_by('job_applications.application_date', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan statistik lamaran berdasarkan bulan
    public function dapatkan_statistik_lamaran_bulanan($year) {
        $this->db->select('MONTH(application_date) as month, COUNT(*) as count');
        $this->db->from('job_applications');
        $this->db->where('YEAR(application_date)', $year);
        $this->db->group_by('MONTH(application_date)');
        $query = $this->db->get();
        return $query->result();
    }

    // Hitung lamaran berdasarkan lowongan
    public function hitung_lamaran_berdasarkan_lowongan($job_id) {
        $this->db->where('job_id', $job_id);
        $query = $this->db->get('job_applications');
        return $query->num_rows();
    }

    // Dapatkan lamaran dengan paginasi
    public function dapatkan_lamaran_paginasi($limit, $start) {
        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.deadline, users.full_name as applicant_name, users.email as applicant_email, users.profile_picture');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->join('users', 'users.id = job_applications.applicant_id', 'left');
        $this->db->order_by('job_applications.id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    // Filter lamaran berdasarkan status
    public function filter_lamaran_berdasarkan_status($status) {
        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.deadline, users.full_name as applicant_name, users.email as applicant_email, users.profile_picture');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->join('users', 'users.id = job_applications.applicant_id', 'left');
        $this->db->where('job_applications.status', $status);
        $this->db->order_by('job_applications.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Hitung lamaran berdasarkan status
    public function hitung_lamaran_berdasarkan_status($status) {
        $this->db->where('status', $status);
        $query = $this->db->get('job_applications');
        return $query->num_rows();
    }

    // Dapatkan jumlah lamaran per lowongan
    public function dapatkan_jumlah_lamaran_per_lowongan() {
        $this->db->select('job_postings.id, job_postings.title, COUNT(job_applications.id) as application_count');
        $this->db->from('job_postings');
        $this->db->join('job_applications', 'job_applications.job_id = job_postings.id', 'left');
        $this->db->where('job_postings.status', 'active');
        $this->db->group_by('job_postings.id');
        $this->db->order_by('application_count', 'DESC');
        $this->db->limit(10); // Ambil 10 lowongan teratas
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan semua lamaran aktif
    public function dapatkan_semua_lamaran_aktif() {
        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.deadline, users.full_name as applicant_name, users.email as applicant_email, users.profile_picture');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->join('users', 'users.id = job_applications.applicant_id', 'left');
        $this->db->where_in('job_applications.status', ['Pending', 'Direview', 'Seleksi', 'Wawancara']);
        $this->db->order_by('job_applications.application_date', 'DESC');
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
            'rejected' => 'seleksi'
        ];

        // Perbarui status lama ke status baru
        foreach ($status_mapping as $old_status => $new_status) {
            $this->db->where('status', $old_status);
            $this->db->update('job_applications', ['status' => $new_status]);
        }

        return true;
    }
}

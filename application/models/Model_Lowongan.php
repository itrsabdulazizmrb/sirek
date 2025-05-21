<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Lowongan extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Dapatkan semua lowongan
    public function dapatkan_lowongan_semua() {
        $this->db->select('job_postings.*, job_categories.name as category_name, users.full_name as created_by_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->join('users', 'users.id = job_postings.created_by', 'left');
        $this->db->order_by('job_postings.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan lowongan berdasarkan ID
    public function dapatkan_lowongan($id) {
        $this->db->select('job_postings.*, job_categories.name as category_name, users.full_name as created_by_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->join('users', 'users.id = job_postings.created_by', 'left');
        $this->db->where('job_postings.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Tambah lowongan baru
    public function tambah_lowongan($data) {
        $this->db->insert('job_postings', $data);
        return $this->db->insert_id();
    }

    // Perbarui lowongan
    public function perbarui_lowongan($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('job_postings', $data);
    }

    // Hapus lowongan
    public function hapus_lowongan($id) {
        $this->db->where('id', $id);
        return $this->db->delete('job_postings');
    }

    // Hitung total lowongan
    public function hitung_lowongan() {
        return $this->db->count_all('job_postings');
    }

    // Hitung lowongan aktif
    public function hitung_lowongan_aktif() {
        $this->db->where('status', 'active');
        $query = $this->db->get('job_postings');
        return $query->num_rows();
    }

    // Dapatkan lowongan aktif dengan paginasi
    public function dapatkan_lowongan_aktif($limit, $start) {
        $this->db->select('job_postings.*, job_categories.name as category_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->where('job_postings.status', 'active');
        $this->db->where('job_postings.deadline >=', date('Y-m-d'));
        $this->db->order_by('job_postings.id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan lowongan unggulan
    public function dapatkan_lowongan_unggulan($limit) {
        $this->db->select('job_postings.*, job_categories.name as category_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->where('job_postings.status', 'active');
        $this->db->where('job_postings.featured', 1);
        $this->db->where('job_postings.deadline >=', date('Y-m-d'));
        $this->db->order_by('job_postings.id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan lowongan terkait
    public function dapatkan_lowongan_terkait($id, $category_id, $limit) {
        $this->db->select('job_postings.*, job_categories.name as category_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->where('job_postings.id !=', $id);
        $this->db->where('job_postings.status', 'active');
        $this->db->where('job_postings.deadline >=', date('Y-m-d'));
        
        if ($category_id) {
            $this->db->where('job_postings.category_id', $category_id);
        }
        
        $this->db->order_by('job_postings.id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan lowongan rekomendasi untuk pelamar
    public function dapatkan_lowongan_rekomendasi($user_id, $limit) {
        // Dapatkan profil pelamar
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('applicant_profiles');
        $profile = $query->row();
        
        // Jika profil ditemukan dan memiliki skills
        if ($profile && $profile->skills) {
            $skills = explode(',', $profile->skills);
            $skills_array = array_map('trim', $skills);
            
            $this->db->select('job_postings.*, job_categories.name as category_name');
            $this->db->from('job_postings');
            $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
            $this->db->where('job_postings.status', 'active');
            $this->db->where('job_postings.deadline >=', date('Y-m-d'));
            
            // Cari lowongan yang sesuai dengan skills
            $this->db->group_start();
            foreach ($skills_array as $skill) {
                $this->db->or_like('job_postings.requirements', $skill);
                $this->db->or_like('job_postings.description', $skill);
            }
            $this->db->group_end();
            
            // Cek apakah sudah melamar
            $this->db->where_not_in('job_postings.id', function($query) use ($user_id) {
                $query->select('job_id')
                      ->from('job_applications')
                      ->where('applicant_id', $user_id);
            });
            
            $this->db->order_by('job_postings.id', 'DESC');
            $this->db->limit($limit);
            $query = $this->db->get();
            return $query->result();
        } else {
            // Jika tidak ada profil atau skills, tampilkan lowongan terbaru
            $this->db->select('job_postings.*, job_categories.name as category_name');
            $this->db->from('job_postings');
            $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
            $this->db->where('job_postings.status', 'active');
            $this->db->where('job_postings.deadline >=', date('Y-m-d'));
            $this->db->order_by('job_postings.id', 'DESC');
            $this->db->limit($limit);
            $query = $this->db->get();
            return $query->result();
        }
    }

    // Cari lowongan
    public function cari_lowongan($keyword) {
        $this->db->select('job_postings.*, job_categories.name as category_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->where('job_postings.status', 'active');
        $this->db->where('job_postings.deadline >=', date('Y-m-d'));
        $this->db->group_start();
        $this->db->like('job_postings.title', $keyword);
        $this->db->or_like('job_postings.description', $keyword);
        $this->db->or_like('job_postings.requirements', $keyword);
        $this->db->or_like('job_postings.responsibilities', $keyword);
        $this->db->or_like('job_postings.location', $keyword);
        $this->db->or_like('job_categories.name', $keyword);
        $this->db->group_end();
        $this->db->order_by('job_postings.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
}

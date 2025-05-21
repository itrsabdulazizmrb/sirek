<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Kategori extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Dapatkan semua kategori lowongan
    public function dapatkan_kategori_lowongan() {
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('job_categories');
        return $query->result();
    }

    // Dapatkan kategori lowongan berdasarkan ID
    public function dapatkan_kategori_lowongan_dari_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('job_categories');
        return $query->row();
    }

    // Tambah kategori lowongan
    public function tambah_kategori_lowongan($data) {
        $this->db->insert('job_categories', $data);
        return $this->db->insert_id();
    }

    // Perbarui kategori lowongan
    public function perbarui_kategori_lowongan($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('job_categories', $data);
    }

    // Hapus kategori lowongan
    public function hapus_kategori_lowongan($id) {
        $this->db->where('id', $id);
        return $this->db->delete('job_categories');
    }

    // Dapatkan semua kategori blog
    public function dapatkan_kategori_blog() {
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('blog_categories');
        return $query->result();
    }

    // Dapatkan kategori blog berdasarkan ID
    public function dapatkan_kategori_blog_dari_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('blog_categories');
        return $query->row();
    }

    // Tambah kategori blog
    public function tambah_kategori_blog($data) {
        $this->db->insert('blog_categories', $data);
        return $this->db->insert_id();
    }

    // Perbarui kategori blog
    public function perbarui_kategori_blog($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('blog_categories', $data);
    }

    // Hapus kategori blog
    public function hapus_kategori_blog($id) {
        $this->db->where('id', $id);
        return $this->db->delete('blog_categories');
    }

    // Hitung lowongan berdasarkan kategori
    public function hitung_lowongan_berdasarkan_kategori($category_id) {
        $this->db->where('category_id', $category_id);
        $query = $this->db->get('job_postings');
        return $query->num_rows();
    }

    // Hitung artikel berdasarkan kategori
    public function hitung_artikel_berdasarkan_kategori($category_id) {
        $this->db->from('blog_post_categories');
        $this->db->where('category_id', $category_id);
        return $this->db->count_all_results();
    }

    // Dapatkan kategori lowongan dengan jumlah lowongan
    public function dapatkan_kategori_lowongan_dengan_jumlah() {
        $this->db->select('job_categories.*, COUNT(job_postings.id) as job_count');
        $this->db->from('job_categories');
        $this->db->join('job_postings', 'job_postings.category_id = job_categories.id', 'left');
        $this->db->group_by('job_categories.id');
        $this->db->order_by('job_categories.name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan kategori blog dengan jumlah artikel
    public function dapatkan_kategori_blog_dengan_jumlah() {
        $this->db->select('blog_categories.*, COUNT(blog_post_categories.post_id) as post_count');
        $this->db->from('blog_categories');
        $this->db->join('blog_post_categories', 'blog_post_categories.category_id = blog_categories.id', 'left');
        $this->db->group_by('blog_categories.id');
        $this->db->order_by('blog_categories.name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
}

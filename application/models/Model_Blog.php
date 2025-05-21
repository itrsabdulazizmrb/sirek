<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Blog extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Dapatkan semua artikel
    public function dapatkan_artikel_semua() {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->order_by('blog_posts.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan artikel berdasarkan ID
    public function dapatkan_artikel($id) {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->where('blog_posts.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Dapatkan artikel berdasarkan slug
    public function dapatkan_artikel_dari_slug($slug) {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->where('blog_posts.slug', $slug);
        $query = $this->db->get();
        return $query->row();
    }

    // Tambah artikel baru
    public function tambah_artikel($data) {
        $this->db->insert('blog_posts', $data);
        return $this->db->insert_id();
    }

    // Perbarui artikel
    public function perbarui_artikel($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('blog_posts', $data);
    }

    // Hapus artikel
    public function hapus_artikel($id) {
        $this->db->where('id', $id);
        return $this->db->delete('blog_posts');
    }

    // Dapatkan artikel terbaru
    public function dapatkan_artikel_terbaru($limit) {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->where('blog_posts.status', 'published');
        $this->db->order_by('blog_posts.created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan artikel terpublikasi dengan paginasi
    public function dapatkan_artikel_terpublikasi($limit, $start) {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->where('blog_posts.status', 'published');
        $this->db->order_by('blog_posts.created_at', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    // Hitung artikel terpublikasi
    public function hitung_artikel_terpublikasi() {
        $this->db->where('status', 'published');
        return $this->db->count_all_results('blog_posts');
    }

    // Hitung total artikel
    public function hitung_artikel() {
        return $this->db->count_all('blog_posts');
    }

    // Tambah kategori artikel
    public function tambah_kategori_artikel($post_id, $category_id) {
        $data = array(
            'post_id' => $post_id,
            'category_id' => $category_id
        );
        return $this->db->insert('blog_post_categories', $data);
    }

    // Hapus semua kategori artikel
    public function hapus_semua_kategori_artikel($post_id) {
        $this->db->where('post_id', $post_id);
        return $this->db->delete('blog_post_categories');
    }

    // Dapatkan kategori artikel
    public function dapatkan_kategori_artikel($post_id) {
        $this->db->select('blog_categories.*');
        $this->db->from('blog_post_categories');
        $this->db->join('blog_categories', 'blog_categories.id = blog_post_categories.category_id', 'left');
        $this->db->where('blog_post_categories.post_id', $post_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan artikel berdasarkan kategori
    public function dapatkan_artikel_berdasarkan_kategori($category_id, $limit = null) {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('blog_post_categories', 'blog_post_categories.post_id = blog_posts.id', 'left');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->where('blog_post_categories.category_id', $category_id);
        $this->db->where('blog_posts.status', 'published');
        $this->db->order_by('blog_posts.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan artikel terkait
    public function dapatkan_artikel_terkait($post_id, $limit) {
        // Dapatkan kategori artikel saat ini
        $categories = $this->dapatkan_kategori_artikel($post_id);
        $category_ids = array();
        
        foreach ($categories as $category) {
            $category_ids[] = $category->id;
        }
        
        if (empty($category_ids)) {
            // Jika tidak ada kategori, tampilkan artikel terbaru
            $this->db->select('blog_posts.*, users.full_name as author_name');
            $this->db->from('blog_posts');
            $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
            $this->db->where('blog_posts.id !=', $post_id);
            $this->db->where('blog_posts.status', 'published');
            $this->db->order_by('blog_posts.created_at', 'DESC');
            $this->db->limit($limit);
            $query = $this->db->get();
            return $query->result();
        } else {
            // Jika ada kategori, tampilkan artikel dengan kategori yang sama
            $this->db->select('blog_posts.*, users.full_name as author_name');
            $this->db->from('blog_posts');
            $this->db->join('blog_post_categories', 'blog_post_categories.post_id = blog_posts.id', 'left');
            $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
            $this->db->where('blog_posts.id !=', $post_id);
            $this->db->where('blog_posts.status', 'published');
            $this->db->where_in('blog_post_categories.category_id', $category_ids);
            $this->db->group_by('blog_posts.id');
            $this->db->order_by('blog_posts.created_at', 'DESC');
            $this->db->limit($limit);
            $query = $this->db->get();
            return $query->result();
        }
    }

    // Tambah dilihat
    public function tambah_dilihat($post_id) {
        $this->db->set('views', 'views+1', FALSE);
        $this->db->where('id', $post_id);
        return $this->db->update('blog_posts');
    }

    // Cari artikel
    public function cari_artikel($keyword) {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->where('blog_posts.status', 'published');
        $this->db->group_start();
        $this->db->like('blog_posts.title', $keyword);
        $this->db->or_like('blog_posts.content', $keyword);
        $this->db->group_end();
        $this->db->order_by('blog_posts.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
}

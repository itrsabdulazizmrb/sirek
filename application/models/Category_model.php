<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get all job categories
    public function get_job_categories() {
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('job_categories');
        return $query->result();
    }

    // Get job category by ID
    public function get_job_category($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('job_categories');
        return $query->row();
    }

    // Insert job category
    public function insert_job_category($data) {
        $this->db->insert('job_categories', $data);
        return $this->db->insert_id();
    }

    // Update job category
    public function update_job_category($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('job_categories', $data);
    }

    // Delete job category
    public function delete_job_category($id) {
        $this->db->where('id', $id);
        return $this->db->delete('job_categories');
    }

    // Get all blog categories
    public function get_blog_categories() {
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('blog_categories');
        return $query->result();
    }

    // Get blog category by ID
    public function get_blog_category($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('blog_categories');
        return $query->row();
    }

    // Get blog category by slug
    public function get_blog_category_by_slug($slug) {
        $this->db->where('slug', $slug);
        $query = $this->db->get('blog_categories');
        return $query->row();
    }

    // Insert blog category
    public function insert_blog_category($data) {
        $this->db->insert('blog_categories', $data);
        return $this->db->insert_id();
    }

    // Update blog category
    public function update_blog_category($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('blog_categories', $data);
    }

    // Delete blog category
    public function delete_blog_category($id) {
        $this->db->where('id', $id);
        return $this->db->delete('blog_categories');
    }

    // Get posts by category
    public function get_posts_by_category($category_id, $limit, $offset) {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->join('blog_post_categories', 'blog_post_categories.post_id = blog_posts.id', 'inner');
        $this->db->where('blog_posts.status', 'published');
        $this->db->where('blog_post_categories.category_id', $category_id);
        $this->db->order_by('blog_posts.id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    // Count posts by category
    public function count_posts_by_category($category_id) {
        $this->db->from('blog_posts');
        $this->db->join('blog_post_categories', 'blog_post_categories.post_id = blog_posts.id', 'inner');
        $this->db->where('blog_posts.status', 'published');
        $this->db->where('blog_post_categories.category_id', $category_id);
        return $this->db->count_all_results();
    }

    // Get jobs by category
    public function get_jobs_by_category($category_id, $limit, $offset) {
        $this->db->select('job_postings.*, job_categories.name as category_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->where('job_postings.status', 'active');
        $this->db->where('job_postings.deadline >=', date('Y-m-d'));
        $this->db->where('job_postings.category_id', $category_id);
        $this->db->order_by('job_postings.featured', 'DESC');
        $this->db->order_by('job_postings.id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    // Count jobs by category
    public function count_jobs_by_category($category_id) {
        $this->db->from('job_postings');
        $this->db->where('status', 'active');
        $this->db->where('deadline >=', date('Y-m-d'));
        $this->db->where('category_id', $category_id);
        return $this->db->count_all_results();
    }
}

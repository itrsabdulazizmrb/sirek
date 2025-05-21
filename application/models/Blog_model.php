<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get all blog posts
    public function get_posts() {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->order_by('blog_posts.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get published posts with pagination
    public function get_published_posts($limit, $offset) {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->where('blog_posts.status', 'published');
        $this->db->order_by('blog_posts.id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    // Get latest published posts
    public function get_latest_posts($limit) {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->where('blog_posts.status', 'published');
        $this->db->order_by('blog_posts.id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    // Get post by ID
    public function get_post($id) {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->where('blog_posts.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Get post by slug
    public function get_post_by_slug($slug) {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->where('blog_posts.slug', $slug);
        $query = $this->db->get();
        return $query->row();
    }

    // Count all published posts
    public function count_published_posts() {
        $this->db->where('status', 'published');
        $query = $this->db->get('blog_posts');
        return $query->num_rows();
    }

    // Count all posts
    public function count_posts() {
        $query = $this->db->get('blog_posts');
        return $query->num_rows();
    }

    // Insert blog post
    public function insert_post($data) {
        $this->db->insert('blog_posts', $data);
        return $this->db->insert_id();
    }

    // Update blog post
    public function update_post($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('blog_posts', $data);
    }

    // Delete blog post
    public function delete_post($id) {
        $this->db->where('id', $id);
        return $this->db->delete('blog_posts');
    }

    // Add post category
    public function add_post_category($post_id, $category_id) {
        $data = array(
            'post_id' => $post_id,
            'category_id' => $category_id
        );
        return $this->db->insert('blog_post_categories', $data);
    }

    // Get post categories
    public function get_post_categories($post_id) {
        $this->db->select('blog_categories.*');
        $this->db->from('blog_categories');
        $this->db->join('blog_post_categories', 'blog_post_categories.category_id = blog_categories.id', 'inner');
        $this->db->where('blog_post_categories.post_id', $post_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Remove all post categories
    public function remove_all_post_categories($post_id) {
        $this->db->where('post_id', $post_id);
        return $this->db->delete('blog_post_categories');
    }

    // Remove post category
    public function remove_post_category($post_id, $category_id) {
        $this->db->where('post_id', $post_id);
        $this->db->where('category_id', $category_id);
        return $this->db->delete('blog_post_categories');
    }

    // Increment post views
    public function increment_views($post_id) {
        $this->db->set('views', 'IFNULL(views, 0) + 1', FALSE);
        $this->db->where('id', $post_id);
        return $this->db->update('blog_posts');
    }

    // Get related posts
    public function get_related_posts($post_id, $limit) {
        // Get categories of current post
        $categories = $this->get_post_categories($post_id);

        if (empty($categories)) {
            // If no categories, return latest posts
            return $this->get_latest_posts($limit);
        }

        // Get category IDs
        $category_ids = array();
        foreach ($categories as $category) {
            $category_ids[] = $category->id;
        }

        // Get posts in same categories
        $this->db->distinct();
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->join('blog_post_categories', 'blog_post_categories.post_id = blog_posts.id', 'inner');
        $this->db->where('blog_posts.status', 'published');
        $this->db->where('blog_posts.id !=', $post_id);
        $this->db->where_in('blog_post_categories.category_id', $category_ids);
        $this->db->order_by('blog_posts.id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    // Search blog posts
    public function search_posts($query) {
        $this->db->select('blog_posts.*, users.full_name as author_name');
        $this->db->from('blog_posts');
        $this->db->join('users', 'users.id = blog_posts.author_id', 'left');
        $this->db->where('blog_posts.status', 'published');
        $this->db->group_start();
        $this->db->like('blog_posts.title', $query);
        $this->db->or_like('blog_posts.content', $query);
        $this->db->group_end();
        $this->db->order_by('blog_posts.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Count posts by category
    public function count_posts_by_category($category_id) {
        $this->db->distinct();
        $this->db->select('COUNT(blog_posts.id) as count');
        $this->db->from('blog_posts');
        $this->db->join('blog_post_categories', 'blog_post_categories.post_id = blog_posts.id', 'inner');
        $this->db->where('blog_posts.status', 'published');
        $this->db->where('blog_post_categories.category_id', $category_id);
        $query = $this->db->get();
        return $query->row()->count;
    }
}

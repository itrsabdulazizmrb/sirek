<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get user by ID
    public function get_user($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Get user by username
    public function get_user_by_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Get user by email
    public function get_user_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Get user by reset token
    public function get_user_by_reset_token($token) {
        $this->db->where('reset_token', $token);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Insert new user
    public function insert_user($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    // Update user
    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    // Update user password
    public function update_password($id, $password) {
        $this->db->where('id', $id);
        return $this->db->update('users', array('password' => $password, 'reset_token' => NULL));
    }

    // Update last login time
    public function update_last_login($id) {
        $this->db->where('id', $id);
        return $this->db->update('users', array('last_login' => date('Y-m-d H:i:s')));
    }

    // Save reset token
    public function save_reset_token($id, $token) {
        $this->db->where('id', $id);
        return $this->db->update('users', array('reset_token' => $token));
    }

    // Get all users
    public function get_users() {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('users');
        return $query->result();
    }

    // Get users by role
    public function get_users_by_role($role) {
        $this->db->where('role', $role);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('users');
        return $query->result();
    }

    // Count all users
    public function count_users() {
        return $this->db->count_all('users');
    }

    // Count users by role
    public function count_users_by_role($role) {
        $this->db->where('role', $role);
        $query = $this->db->get('users');
        return $query->num_rows();
    }

    // Count applicants
    public function count_applicants() {
        $this->db->where('role', 'applicant');
        $query = $this->db->get('users');
        return $query->num_rows();
    }

    // Delete user
    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    // Search users
    public function search_users($query) {
        $this->db->like('username', $query);
        $this->db->or_like('email', $query);
        $this->db->or_like('full_name', $query);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('users');
        return $query->result();
    }
}

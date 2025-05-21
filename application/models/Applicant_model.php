<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get applicant profile by user ID
    public function get_profile($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('applicant_profiles');
        return $query->row();
    }

    // Create empty profile for new applicant
    public function create_profile($user_id) {
        $data = array(
            'user_id' => $user_id
        );
        return $this->db->insert('applicant_profiles', $data);
    }

    // Insert applicant profile with data
    public function insert_profile($data) {
        return $this->db->insert('applicant_profiles', $data);
    }

    // Update applicant profile
    public function update_profile($user_id, $data) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('applicant_profiles', $data);
    }

    // Get profile completion percentage
    public function get_profile_completion($user_id) {
        // Get user and profile data
        $this->db->where('id', $user_id);
        $user_query = $this->db->get('users');
        $user = $user_query->row();

        $this->db->where('user_id', $user_id);
        $profile_query = $this->db->get('applicant_profiles');
        $profile = $profile_query->row();

        if (!$user || !$profile) {
            return 0;
        }

        // Define required fields
        $required_fields = array(
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'profile_picture' => $user->profile_picture,
            'date_of_birth' => $profile->date_of_birth,
            'gender' => $profile->gender,
            'education' => $profile->education,
            'experience' => $profile->experience,
            'skills' => $profile->skills,
            'resume' => $profile->resume
        );

        // Count completed fields
        $completed = 0;
        foreach ($required_fields as $field => $value) {
            if (!empty($value)) {
                $completed++;
            }
        }

        // Calculate percentage
        return round(($completed / count($required_fields)) * 100);
    }

    // Get all applicants with profiles
    public function get_applicants() {
        $this->db->select('users.*, applicant_profiles.date_of_birth, applicant_profiles.gender, applicant_profiles.education, applicant_profiles.experience, applicant_profiles.skills, applicant_profiles.resume');
        $this->db->from('users');
        $this->db->join('applicant_profiles', 'applicant_profiles.user_id = users.id', 'left');
        $this->db->where('users.role', 'applicant');
        $this->db->order_by('users.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Search applicants
    public function search_applicants($query) {
        $this->db->select('users.*, applicant_profiles.date_of_birth, applicant_profiles.gender, applicant_profiles.education, applicant_profiles.experience, applicant_profiles.skills, applicant_profiles.resume');
        $this->db->from('users');
        $this->db->join('applicant_profiles', 'applicant_profiles.user_id = users.id', 'left');
        $this->db->where('users.role', 'applicant');
        $this->db->group_start();
        $this->db->like('users.username', $query);
        $this->db->or_like('users.email', $query);
        $this->db->or_like('users.full_name', $query);
        $this->db->or_like('applicant_profiles.skills', $query);
        $this->db->or_like('applicant_profiles.education', $query);
        $this->db->or_like('applicant_profiles.experience', $query);
        $this->db->group_end();
        $this->db->order_by('users.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
}

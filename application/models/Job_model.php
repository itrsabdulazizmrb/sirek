<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get all jobs
    public function get_jobs() {
        $this->db->select('job_postings.*, job_categories.name as category_name, users.full_name as created_by_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->join('users', 'users.id = job_postings.created_by', 'left');
        $this->db->order_by('job_postings.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get job by ID
    public function get_job($id) {
        $this->db->select('job_postings.*, job_categories.name as category_name, users.full_name as created_by_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->join('users', 'users.id = job_postings.created_by', 'left');
        $this->db->where('job_postings.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Get active jobs with pagination
    public function get_active_jobs($limit, $offset) {
        $this->db->select('job_postings.*, job_categories.name as category_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->where('job_postings.status', 'active');
        $this->db->where('job_postings.deadline >=', date('Y-m-d'));
        $this->db->order_by('job_postings.featured', 'DESC');
        $this->db->order_by('job_postings.id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    // Get featured jobs
    public function get_featured_jobs($limit) {
        $this->db->select('job_postings.*, job_categories.name as category_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->where('job_postings.status', 'active');
        $this->db->where('job_postings.deadline >=', date('Y-m-d'));
        $this->db->where('job_postings.featured', 1);
        $this->db->order_by('job_postings.id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    // Get related jobs
    public function get_related_jobs($job_id, $category_id, $limit) {
        $this->db->select('job_postings.*, job_categories.name as category_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->where('job_postings.status', 'active');
        $this->db->where('job_postings.deadline >=', date('Y-m-d'));
        $this->db->where('job_postings.id !=', $job_id);
        $this->db->where('job_postings.category_id', $category_id);
        $this->db->order_by('job_postings.id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    // Get recommended jobs for applicant
    public function get_recommended_jobs($user_id, $limit) {
        // Get applicant's skills
        $this->db->select('skills');
        $this->db->from('applicant_profiles');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        $profile = $query->row();

        if ($profile && $profile->skills) {
            // Get jobs that match applicant's skills
            $skills = explode(',', $profile->skills);
            $this->db->select('job_postings.*, job_categories.name as category_name');
            $this->db->from('job_postings');
            $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
            $this->db->where('job_postings.status', 'active');
            $this->db->where('job_postings.deadline >=', date('Y-m-d'));

            // Check if job requirements contain any of the applicant's skills
            $skill_conditions = array();
            foreach ($skills as $skill) {
                $skill = trim($skill);
                if ($skill) {
                    $skill_conditions[] = "job_postings.requirements LIKE '%$skill%'";
                }
            }

            if (!empty($skill_conditions)) {
                $this->db->where('(' . implode(' OR ', $skill_conditions) . ')');
            }

            // Exclude jobs that the applicant has already applied for
            $this->db->join('job_applications', 'job_applications.job_id = job_postings.id AND job_applications.applicant_id = ' . $user_id, 'left');
            $this->db->where('job_applications.id IS NULL');

            $this->db->order_by('job_postings.featured', 'DESC');
            $this->db->order_by('job_postings.id', 'DESC');
            $this->db->limit($limit);
            $query = $this->db->get();
            return $query->result();
        } else {
            // If no skills found, return latest active jobs
            return $this->get_active_jobs($limit, 0);
        }
    }

    // Count all jobs
    public function count_jobs() {
        return $this->db->count_all('job_postings');
    }

    // Count active jobs
    public function count_active_jobs() {
        $this->db->where('status', 'active');
        $this->db->where('deadline >=', date('Y-m-d'));
        $query = $this->db->get('job_postings');
        return $query->num_rows();
    }

    // Get all active jobs (without pagination)
    public function get_all_active_jobs() {
        $this->db->select('job_postings.*, job_categories.name as category_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->where('job_postings.status', 'active');
        $this->db->where('job_postings.deadline >=', date('Y-m-d'));
        $this->db->order_by('job_postings.featured', 'DESC');
        $this->db->order_by('job_postings.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Insert job
    public function insert_job($data) {
        $this->db->insert('job_postings', $data);
        return $this->db->insert_id();
    }

    // Update job
    public function update_job($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('job_postings', $data);
    }

    // Delete job
    public function delete_job($id) {
        $this->db->where('id', $id);
        return $this->db->delete('job_postings');
    }

    // Search jobs
    public function search_jobs($query) {
        $this->db->select('job_postings.*, job_categories.name as category_name');
        $this->db->from('job_postings');
        $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
        $this->db->where('job_postings.status', 'active');
        $this->db->where('job_postings.deadline >=', date('Y-m-d'));
        $this->db->group_start();
        $this->db->like('job_postings.title', $query);
        $this->db->or_like('job_postings.description', $query);
        $this->db->or_like('job_postings.requirements', $query);
        $this->db->or_like('job_postings.responsibilities', $query);
        $this->db->or_like('job_postings.location', $query);
        $this->db->or_like('job_categories.name', $query);
        $this->db->group_end();
        $this->db->order_by('job_postings.featured', 'DESC');
        $this->db->order_by('job_postings.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
}

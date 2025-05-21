<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get all applications
    public function get_applications() {
        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.deadline, users.full_name as applicant_name, users.email as applicant_email, users.profile_picture');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->join('users', 'users.id = job_applications.applicant_id', 'left');
        $this->db->order_by('job_applications.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get application by ID
    public function get_application($id) {
        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.deadline, users.full_name as applicant_name, users.email as applicant_email, users.profile_picture');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->join('users', 'users.id = job_applications.applicant_id', 'left');
        $this->db->where('job_applications.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Get applications by job ID
    public function get_job_applications($job_id) {
        $this->db->select('job_applications.*, job_postings.deadline, users.full_name as applicant_name, users.email as applicant_email, users.profile_picture');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->join('users', 'users.id = job_applications.applicant_id', 'left');
        $this->db->where('job_applications.job_id', $job_id);
        $this->db->order_by('job_applications.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get applications by applicant ID
    public function get_applicant_applications($applicant_id) {
        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.location as job_location, job_postings.job_type as job_type, job_postings.deadline');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->where('job_applications.applicant_id', $applicant_id);
        $this->db->order_by('job_applications.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get recent applications
    public function get_recent_applications($limit) {
        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.deadline, users.full_name as applicant_name, users.profile_picture');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->join('users', 'users.id = job_applications.applicant_id', 'left');
        $this->db->order_by('job_applications.application_date', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    // Count all applications
    public function count_applications() {
        return $this->db->count_all('job_applications');
    }

    // Count new applications (pending status)
    public function count_new_applications() {
        $this->db->where('status', 'pending');
        $query = $this->db->get('job_applications');
        return $query->num_rows();
    }

    // Insert application
    public function insert_application($data) {
        $this->db->insert('job_applications', $data);
        return $this->db->insert_id();
    }

    // Update application status
    public function update_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('job_applications', array('status' => $status));
    }

    // Delete application
    public function delete_application($id) {
        $this->db->where('id', $id);
        return $this->db->delete('job_applications');
    }

    // Check if applicant has already applied for a job
    public function has_applied($applicant_id, $job_id) {
        $this->db->where('applicant_id', $applicant_id);
        $this->db->where('job_id', $job_id);
        $query = $this->db->get('job_applications');
        return ($query->num_rows() > 0);
    }

    // Get application statistics by status
    public function get_application_stats() {
        $this->db->select('status, COUNT(*) as count');
        $this->db->from('job_applications');
        $this->db->group_by('status');
        $query = $this->db->get();
        return $query->result();
    }

    // Get application statistics by month
    public function get_monthly_application_stats($year) {
        $this->db->select('MONTH(application_date) as month, COUNT(*) as count');
        $this->db->from('job_applications');
        $this->db->where('YEAR(application_date)', $year);
        $this->db->group_by('MONTH(application_date)');
        $query = $this->db->get();
        return $query->result();
    }

    // Count applications by job ID
    public function count_applications_by_job($job_id) {
        $this->db->where('job_id', $job_id);
        $query = $this->db->get('job_applications');
        return $query->num_rows();
    }
}

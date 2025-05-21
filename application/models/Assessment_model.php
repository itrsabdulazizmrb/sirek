<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get all assessments
    public function get_assessments() {
        $this->db->select('assessments.*, assessment_types.name as type_name, users.full_name as created_by_name');
        $this->db->from('assessments');
        $this->db->join('assessment_types', 'assessment_types.id = assessments.type_id', 'left');
        $this->db->join('users', 'users.id = assessments.created_by', 'left');
        $this->db->order_by('assessments.id', 'DESC');
        $query = $this->db->get();
        $assessments = $query->result();

        // Add question count for each assessment
        foreach ($assessments as $assessment) {
            $this->db->where('assessment_id', $assessment->id);
            $question_query = $this->db->get('questions');
            $assessment->question_count = $question_query->num_rows();
        }

        return $assessments;
    }

    // Get assessment by ID
    public function get_assessment($id) {
        $this->db->select('assessments.*, assessment_types.name as type_name, users.full_name as created_by_name');
        $this->db->from('assessments');
        $this->db->join('assessment_types', 'assessment_types.id = assessments.type_id', 'left');
        $this->db->join('users', 'users.id = assessments.created_by', 'left');
        $this->db->where('assessments.id', $id);
        $query = $this->db->get();
        $assessment = $query->row();

        if ($assessment) {
            // Add question count
            $this->db->where('assessment_id', $assessment->id);
            $question_query = $this->db->get('questions');
            $assessment->question_count = $question_query->num_rows();
        }

        return $assessment;
    }

    // Get assessment types
    public function get_assessment_types() {
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('assessment_types');
        return $query->result();
    }

    // Get assessment questions
    public function get_assessment_questions($assessment_id) {
        $this->db->where('assessment_id', $assessment_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('questions');
        return $query->result();
    }

    // Get question options
    public function get_question_options($question_id) {
        $this->db->where('question_id', $question_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('question_options');
        return $query->result();
    }

    // Insert assessment
    public function insert_assessment($data) {
        $this->db->insert('assessments', $data);
        return $this->db->insert_id();
    }

    // Update assessment
    public function update_assessment($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('assessments', $data);
    }

    // Delete assessment
    public function delete_assessment($id) {
        $this->db->where('id', $id);
        return $this->db->delete('assessments');
    }

    // Insert question
    public function insert_question($data) {
        $this->db->insert('questions', $data);
        return $this->db->insert_id();
    }

    // Update question
    public function update_question($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('questions', $data);
    }

    // Delete question
    public function delete_question($id) {
        $this->db->where('id', $id);
        return $this->db->delete('questions');
    }

    // Insert question option
    public function insert_question_option($data) {
        $this->db->insert('question_options', $data);
        return $this->db->insert_id();
    }

    // Update question option
    public function update_question_option($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('question_options', $data);
    }

    // Delete question option
    public function delete_question_option($id) {
        $this->db->where('id', $id);
        return $this->db->delete('question_options');
    }

    // Assign assessment to job
    public function assign_assessment_to_job($job_id, $assessment_id, $required = true) {
        $data = array(
            'job_id' => $job_id,
            'assessment_id' => $assessment_id,
            'required' => $required ? 1 : 0
        );
        $this->db->insert('job_assessments', $data);
        return $this->db->insert_id();
    }

    // Get job assessments
    public function get_job_assessments($job_id) {
        $this->db->select('job_assessments.*, assessments.title as assessment_title, assessment_types.name as type_name');
        $this->db->from('job_assessments');
        $this->db->join('assessments', 'assessments.id = job_assessments.assessment_id', 'left');
        $this->db->join('assessment_types', 'assessment_types.id = assessments.type_id', 'left');
        $this->db->where('job_assessments.job_id', $job_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Get applicant assessments for a specific application
    public function get_applicant_assessments($application_id) {
        $this->db->select('applicant_assessments.*, assessments.title as assessment_title, assessment_types.name as type_name');
        $this->db->from('applicant_assessments');
        $this->db->join('assessments', 'assessments.id = applicant_assessments.assessment_id', 'left');
        $this->db->join('assessment_types', 'assessment_types.id = assessments.type_id', 'left');
        $this->db->where('applicant_assessments.application_id', $application_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Get all assessments for an applicant
    public function get_applicant_all_assessments($user_id) {
        $this->db->select('applicant_assessments.*, assessments.title as assessment_title, assessment_types.name as type_name, job_applications.job_id, job_postings.title as job_title');
        $this->db->from('applicant_assessments');
        $this->db->join('assessments', 'assessments.id = applicant_assessments.assessment_id', 'left');
        $this->db->join('assessment_types', 'assessment_types.id = assessments.type_id', 'left');
        $this->db->join('job_applications', 'job_applications.id = applicant_assessments.application_id', 'left');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->where('job_applications.applicant_id', $user_id);
        $this->db->order_by('applicant_assessments.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get specific applicant assessment
    public function get_applicant_assessment($application_id, $assessment_id) {
        $this->db->select('applicant_assessments.*, job_applications.applicant_id');
        $this->db->from('applicant_assessments');
        $this->db->join('job_applications', 'job_applications.id = applicant_assessments.application_id', 'left');
        $this->db->where('applicant_assessments.application_id', $application_id);
        $this->db->where('applicant_assessments.assessment_id', $assessment_id);
        $query = $this->db->get();
        return $query->row();
    }

    // Create applicant assessment
    public function create_applicant_assessment($data) {
        $this->db->insert('applicant_assessments', $data);
        return $this->db->insert_id();
    }

    // Update applicant assessment status
    public function update_applicant_assessment_status($id, $status) {
        $data = array(
            'status' => $status
        );

        if ($status == 'in_progress') {
            $data['start_time'] = date('Y-m-d H:i:s');
        } else if ($status == 'completed') {
            $data['end_time'] = date('Y-m-d H:i:s');
        }

        $this->db->where('id', $id);
        return $this->db->update('applicant_assessments', $data);
    }

    // Insert applicant answer
    public function insert_applicant_answer($data) {
        $this->db->insert('applicant_answers', $data);
        return $this->db->insert_id();
    }

    // Get applicant answers
    public function get_applicant_answers($applicant_assessment_id) {
        $this->db->select('applicant_answers.*, questions.question_text, questions.question_type, question_options.option_text');
        $this->db->from('applicant_answers');
        $this->db->join('questions', 'questions.id = applicant_answers.question_id', 'left');
        $this->db->join('question_options', 'question_options.id = applicant_answers.selected_option_id', 'left');
        $this->db->where('applicant_answers.applicant_assessment_id', $applicant_assessment_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Grade applicant assessment
    public function grade_assessment($applicant_assessment_id, $grader_id) {
        // Get all answers for this assessment
        $this->db->where('applicant_assessment_id', $applicant_assessment_id);
        $query = $this->db->get('applicant_answers');
        $answers = $query->result();

        $total_score = 0;

        foreach ($answers as $answer) {
            // For multiple choice questions, check if selected option is correct
            if ($answer->selected_option_id) {
                $this->db->where('id', $answer->selected_option_id);
                $option_query = $this->db->get('question_options');
                $option = $option_query->row();

                if ($option && $option->is_correct) {
                    // Get question points
                    $this->db->where('id', $answer->question_id);
                    $question_query = $this->db->get('questions');
                    $question = $question_query->row();

                    $score = $question->points;
                } else {
                    $score = 0;
                }

                // Update answer score
                $this->db->where('id', $answer->id);
                $this->db->update('applicant_answers', array(
                    'score' => $score,
                    'graded_by' => $grader_id,
                    'graded_at' => date('Y-m-d H:i:s')
                ));

                $total_score += $score;
            }
        }

        // Update assessment score and status
        $this->db->where('id', $applicant_assessment_id);
        return $this->db->update('applicant_assessments', array(
            'score' => $total_score,
            'status' => 'graded'
        ));
    }

    // Count total assessments assigned to an application
    public function count_applicant_assessments($application_id) {
        $this->db->where('application_id', $application_id);
        $query = $this->db->get('applicant_assessments');
        return $query->num_rows();
    }

    // Count completed assessments for an application
    public function count_completed_assessments($application_id) {
        $this->db->where('application_id', $application_id);
        $this->db->where_in('status', ['completed', 'graded']);
        $query = $this->db->get('applicant_assessments');
        return $query->num_rows();
    }

    // Count applicants assigned to an assessment
    public function count_assessment_applicants($assessment_id) {
        $this->db->where('assessment_id', $assessment_id);
        $query = $this->db->get('applicant_assessments');
        return $query->num_rows();
    }

    // Count completed assessments for an assessment
    public function count_assessment_completions($assessment_id) {
        $this->db->where('assessment_id', $assessment_id);
        $this->db->where_in('status', ['completed', 'graded']);
        $query = $this->db->get('applicant_assessments');
        return $query->num_rows();
    }
}

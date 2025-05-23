<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Penilaian extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Dapatkan semua penilaian
    public function dapatkan_semua_penilaian() {
        $this->db->select('assessments.*, assessment_types.name as type_name, users.full_name as created_by_name');
        $this->db->from('assessments');
        $this->db->join('assessment_types', 'assessment_types.id = assessments.type_id', 'left');
        $this->db->join('users', 'users.id = assessments.created_by', 'left');
        $this->db->order_by('assessments.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan penilaian berdasarkan ID
    public function dapatkan_penilaian($id) {
        $this->db->select('assessments.*, assessment_types.name as type_name, users.full_name as created_by_name');
        $this->db->from('assessments');
        $this->db->join('assessment_types', 'assessment_types.id = assessments.type_id', 'left');
        $this->db->join('users', 'users.id = assessments.created_by', 'left');
        $this->db->where('assessments.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Tambah penilaian baru
    public function tambah_penilaian($data) {
        $this->db->insert('assessments', $data);
        return $this->db->insert_id();
    }

    // Perbarui penilaian
    public function perbarui_penilaian($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('assessments', $data);
    }

    // Hapus penilaian
    public function hapus_penilaian($id) {
        $this->db->where('id', $id);
        return $this->db->delete('assessments');
    }

    // Dapatkan jenis penilaian
    public function dapatkan_jenis_penilaian() {
        $query = $this->db->get('assessment_types');
        return $query->result();
    }

    // Dapatkan soal penilaian
    public function dapatkan_soal_penilaian($assessment_id) {
        $this->db->where('assessment_id', $assessment_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('questions');
        return $query->result();
    }

    // Dapatkan soal berdasarkan ID
    public function dapatkan_soal($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('questions');
        return $query->row();
    }

    // Tambah soal baru
    public function tambah_soal($data) {
        $this->db->insert('questions', $data);
        return $this->db->insert_id();
    }

    // Perbarui soal
    public function perbarui_soal($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('questions', $data);
    }

    // Hapus soal
    public function hapus_soal($id) {
        $this->db->where('id', $id);
        return $this->db->delete('questions');
    }

    // Dapatkan opsi soal
    public function dapatkan_opsi_soal($question_id) {
        $this->db->where('question_id', $question_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('question_options');
        return $query->result();
    }

    // Tambah opsi soal
    public function tambah_opsi_soal($data) {
        $this->db->insert('question_options', $data);
        return $this->db->insert_id();
    }

    // Perbarui opsi soal
    public function perbarui_opsi_soal($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('question_options', $data);
    }

    // Hapus opsi soal
    public function hapus_opsi_soal($id) {
        $this->db->where('id', $id);
        return $this->db->delete('question_options');
    }

    // Dapatkan penilaian pelamar
    public function dapatkan_penilaian_pelamar($application_id) {
        $this->db->select('applicant_assessments.*, assessments.title as assessment_title, assessments.description, assessments.passing_score, assessment_types.name as type_name');
        $this->db->from('applicant_assessments');
        $this->db->join('assessments', 'assessments.id = applicant_assessments.assessment_id', 'left');
        $this->db->join('assessment_types', 'assessment_types.id = assessments.type_id', 'left');
        $this->db->where('applicant_assessments.application_id', $application_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan penilaian pelamar spesifik
    public function dapatkan_penilaian_pelamar_spesifik($application_id, $assessment_id) {
        $this->db->select('applicant_assessments.*, assessments.title as assessment_title, assessments.description, assessments.passing_score, assessment_types.name as type_name');
        $this->db->from('applicant_assessments');
        $this->db->join('assessments', 'assessments.id = applicant_assessments.assessment_id', 'left');
        $this->db->join('assessment_types', 'assessment_types.id = assessments.type_id', 'left');
        $this->db->where('applicant_assessments.application_id', $application_id);
        $this->db->where('applicant_assessments.assessment_id', $assessment_id);
        $query = $this->db->get();
        return $query->row();
    }

    // Dapatkan semua penilaian pelamar
    public function dapatkan_semua_penilaian_pelamar($user_id) {
        $this->db->select('applicant_assessments.*, assessments.title as assessment_title, assessments.description, assessments.passing_score, job_applications.job_id, job_postings.title as job_title, assessment_types.name as type_name');
        $this->db->from('applicant_assessments');
        $this->db->join('assessments', 'assessments.id = applicant_assessments.assessment_id', 'left');
        $this->db->join('assessment_types', 'assessment_types.id = assessments.type_id', 'left');
        $this->db->join('job_applications', 'job_applications.id = applicant_assessments.application_id', 'left');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->where('applicant_assessments.applicant_id', $user_id);
        $this->db->order_by('applicant_assessments.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Tambah penilaian pelamar
    public function tambah_penilaian_pelamar($data) {
        $this->db->insert('applicant_assessments', $data);
        return $this->db->insert_id();
    }

    // Perbarui status penilaian pelamar
    public function perbarui_status_penilaian_pelamar($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('applicant_assessments', array('status' => $status, 'completed_at' => date('Y-m-d H:i:s')));
    }

    // Tambah jawaban pelamar
    public function tambah_jawaban_pelamar($data) {
        $this->db->insert('applicant_answers', $data);
        return $this->db->insert_id();
    }

    // Dapatkan jawaban pelamar
    public function dapatkan_jawaban_pelamar($applicant_assessment_id) {
        $this->db->select('applicant_answers.*, questions.question_text, questions.question_type, questions.points');
        $this->db->from('applicant_answers');
        $this->db->join('questions', 'questions.id = applicant_answers.question_id', 'left');
        $this->db->where('applicant_answers.applicant_assessment_id', $applicant_assessment_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Hitung skor penilaian pelamar
    public function hitung_skor_penilaian_pelamar($applicant_assessment_id) {
        $total_points = 0;
        $earned_points = 0;

        // Dapatkan semua jawaban pelamar
        $this->db->select('applicant_answers.*, questions.points, question_options.is_correct');
        $this->db->from('applicant_answers');
        $this->db->join('questions', 'questions.id = applicant_answers.question_id', 'left');
        $this->db->join('question_options', 'question_options.id = applicant_answers.selected_option_id', 'left');
        $this->db->where('applicant_answers.applicant_assessment_id', $applicant_assessment_id);
        $query = $this->db->get();
        $answers = $query->result();

        foreach ($answers as $answer) {
            $total_points += $answer->points;

            if ($answer->is_correct == 1) {
                $earned_points += $answer->points;
            }
        }

        // Perbarui skor di database
        $score = ($total_points > 0) ? round(($earned_points / $total_points) * 100) : 0;
        $this->db->where('id', $applicant_assessment_id);
        $this->db->update('applicant_assessments', array('score' => $score));

        return $score;
    }

    // Tetapkan penilaian ke lowongan
    public function tetapkan_penilaian_ke_lowongan($job_id, $assessment_id) {
        $data = array(
            'job_id' => $job_id,
            'assessment_id' => $assessment_id
        );
        return $this->db->insert('job_assessments', $data);
    }

    // Hapus penilaian dari lowongan
    public function hapus_penilaian_dari_lowongan($job_id, $assessment_id) {
        $this->db->where('job_id', $job_id);
        $this->db->where('assessment_id', $assessment_id);
        return $this->db->delete('job_assessments');
    }

    // Cek apakah penilaian sudah digunakan oleh pelamar
    public function cek_penilaian_digunakan($assessment_id) {
        $this->db->where('assessment_id', $assessment_id);
        $query = $this->db->get('applicant_assessments');
        return ($query->num_rows() > 0);
    }

    // Dapatkan lowongan yang terkait dengan penilaian
    public function dapatkan_lowongan_penilaian($assessment_id) {
        $this->db->select('job_assessments.*, jobs.title as job_title');
        $this->db->from('job_assessments');
        $this->db->join('jobs', 'jobs.id = job_assessments.job_id', 'left');
        $this->db->where('job_assessments.assessment_id', $assessment_id);
        $query = $this->db->get();
        return $query->row();
    }

    // Hitung jumlah soal dalam penilaian
    public function hitung_soal_penilaian($assessment_id) {
        $this->db->where('assessment_id', $assessment_id);
        return $this->db->count_all_results('questions');
    }

    // Hitung jumlah pelamar yang ditugaskan penilaian
    public function hitung_pelamar_penilaian($assessment_id) {
        $this->db->where('assessment_id', $assessment_id);
        return $this->db->count_all_results('applicant_assessments');
    }

    // Hitung jumlah pelamar yang telah menyelesaikan penilaian
    public function hitung_penyelesaian_penilaian($assessment_id) {
        $this->db->where('assessment_id', $assessment_id);
        $this->db->where('status', 'completed');
        return $this->db->count_all_results('applicant_assessments');
    }

    // Dapatkan penilaian aktif
    public function dapatkan_penilaian_aktif() {
        $this->db->select('assessments.*, assessment_types.name as type_name');
        $this->db->from('assessments');
        $this->db->join('assessment_types', 'assessment_types.id = assessments.type_id', 'left');
        $this->db->where('assessments.is_active', 1);
        $this->db->order_by('assessments.title', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan penilaian untuk lowongan tertentu
    public function dapatkan_penilaian_lowongan($job_id) {
        $this->db->select('job_assessments.*, assessments.title as assessment_title, assessment_types.name as type_name');
        $this->db->from('job_assessments');
        $this->db->join('assessments', 'assessments.id = job_assessments.assessment_id', 'left');
        $this->db->join('assessment_types', 'assessment_types.id = assessments.type_id', 'left');
        $this->db->where('job_assessments.job_id', $job_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Hapus semua penilaian dari lowongan
    public function hapus_semua_penilaian_lowongan($job_id) {
        $this->db->where('job_id', $job_id);
        return $this->db->delete('job_assessments');
    }

    // Cek apakah penilaian sudah ditetapkan ke pelamar
    public function cek_penilaian_sudah_ditetapkan($application_id, $assessment_id) {
        $this->db->where('application_id', $application_id);
        $this->db->where('assessment_id', $assessment_id);
        $query = $this->db->get('applicant_assessments');
        return ($query->num_rows() > 0);
    }

    // Hitung jumlah penilaian yang ditetapkan ke pelamar
    public function hitung_penilaian_pelamar($application_id) {
        $this->db->where('application_id', $application_id);
        return $this->db->count_all_results('applicant_assessments');
    }

    // Hitung jumlah penilaian yang telah diselesaikan oleh pelamar
    public function hitung_penilaian_selesai($application_id) {
        $this->db->where('application_id', $application_id);
        $this->db->where('status', 'completed');
        return $this->db->count_all_results('applicant_assessments');
    }

    // Hitung total penilaian
    public function hitung_penilaian() {
        return $this->db->count_all('assessments');
    }

    // Hitung penilaian yang telah diselesaikan
    public function hitung_penilaian_selesai_semua() {
        $this->db->where('status', 'completed');
        return $this->db->count_all_results('applicant_assessments');
    }

    // Dapatkan statistik penilaian berdasarkan bulan
    public function dapatkan_statistik_penilaian_bulanan($year) {
        $this->db->select('MONTH(created_at) as month, COUNT(*) as count');
        $this->db->from('applicant_assessments');
        $this->db->where('YEAR(created_at)', $year);
        $this->db->group_by('MONTH(created_at)');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan statistik skor penilaian
    public function dapatkan_statistik_skor_penilaian() {
        $this->db->select('FLOOR(score/10)*10 as score_range, COUNT(*) as count');
        $this->db->from('applicant_assessments');
        $this->db->where('status', 'completed');
        $this->db->group_by('FLOOR(score/10)');
        $this->db->order_by('score_range', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
}

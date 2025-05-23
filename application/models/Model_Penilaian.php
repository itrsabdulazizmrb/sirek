<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Penilaian extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Dapatkan semua penilaian
    public function dapatkan_semua_penilaian() {
        $this->db->select('penilaian.*, jenis_penilaian.nama as type_name, pengguna.nama_lengkap as created_by_name');
        $this->db->from('penilaian');
        $this->db->join('jenis_penilaian', 'jenis_penilaian.id = penilaian.id_jenis', 'left');
        $this->db->join('pengguna', 'pengguna.id = penilaian.dibuat_oleh', 'left');
        $this->db->order_by('penilaian.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan penilaian berdasarkan ID
    public function dapatkan_penilaian($id) {
        $this->db->select('penilaian.*, jenis_penilaian.nama as type_name, pengguna.nama_lengkap as created_by_name');
        $this->db->from('penilaian');
        $this->db->join('jenis_penilaian', 'jenis_penilaian.id = penilaian.id_jenis', 'left');
        $this->db->join('pengguna', 'pengguna.id = penilaian.dibuat_oleh', 'left');
        $this->db->where('penilaian.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Tambah penilaian baru
    public function tambah_penilaian($data) {
        $this->db->insert('penilaian', $data);
        return $this->db->insert_id();
    }

    // Perbarui penilaian
    public function perbarui_penilaian($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('penilaian', $data);
    }

    // Hapus penilaian
    public function hapus_penilaian($id) {
        $this->db->where('id', $id);
        return $this->db->delete('penilaian');
    }

    // Dapatkan jenis penilaian
    public function dapatkan_jenis_penilaian() {
        $query = $this->db->get('jenis_penilaian');
        return $query->result();
    }

    // Dapatkan soal penilaian
    public function dapatkan_soal_penilaian($assessment_id) {
        $this->db->where('id_penilaian', $assessment_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('soal');
        return $query->result();
    }

    // Dapatkan soal berdasarkan ID
    public function dapatkan_soal($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('soal');
        return $query->row();
    }

    // Tambah soal baru
    public function tambah_soal($data) {
        $this->db->insert('soal', $data);
        return $this->db->insert_id();
    }

    // Perbarui soal
    public function perbarui_soal($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('soal', $data);
    }

    // Hapus soal
    public function hapus_soal($id) {
        $this->db->where('id', $id);
        return $this->db->delete('soal');
    }

    // Dapatkan opsi soal
    public function dapatkan_opsi_soal($question_id) {
        $this->db->where('id_soal', $question_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('pilihan_soal');
        return $query->result();
    }

    // Tambah opsi soal
    public function tambah_opsi_soal($data) {
        $this->db->insert('pilihan_soal', $data);
        return $this->db->insert_id();
    }

    // Perbarui opsi soal
    public function perbarui_opsi_soal($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('pilihan_soal', $data);
    }

    // Hapus opsi soal
    public function hapus_opsi_soal($id) {
        $this->db->where('id', $id);
        return $this->db->delete('pilihan_soal');
    }

    // Dapatkan penilaian pelamar
    public function dapatkan_penilaian_pelamar($application_id) {
        $this->db->select('penilaian_pelamar.*, penilaian.judul as assessment_title, penilaian.deskripsi as description, penilaian.nilai_lulus as passing_score, jenis_penilaian.nama as type_name');
        $this->db->from('penilaian_pelamar');
        $this->db->join('penilaian', 'penilaian.id = penilaian_pelamar.id_penilaian', 'left');
        $this->db->join('jenis_penilaian', 'jenis_penilaian.id = penilaian.id_jenis', 'left');
        $this->db->where('penilaian_pelamar.id_lamaran', $application_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan penilaian pelamar spesifik
    public function dapatkan_penilaian_pelamar_spesifik($application_id, $assessment_id) {
        $this->db->select('penilaian_pelamar.*, penilaian.judul as assessment_title, penilaian.deskripsi as description, penilaian.nilai_lulus as passing_score, jenis_penilaian.nama as type_name');
        $this->db->from('penilaian_pelamar');
        $this->db->join('penilaian', 'penilaian.id = penilaian_pelamar.id_penilaian', 'left');
        $this->db->join('jenis_penilaian', 'jenis_penilaian.id = penilaian.id_jenis', 'left');
        $this->db->where('penilaian_pelamar.id_lamaran', $application_id);
        $this->db->where('penilaian_pelamar.id_penilaian', $assessment_id);
        $query = $this->db->get();
        return $query->row();
    }

    // Dapatkan semua penilaian pelamar
    public function dapatkan_semua_penilaian_pelamar($user_id) {
        $this->db->select('penilaian_pelamar.*, penilaian.judul as assessment_title, penilaian.deskripsi as description, penilaian.nilai_lulus as passing_score, lamaran_pekerjaan.id_pekerjaan as job_id, lowongan_pekerjaan.judul as job_title, jenis_penilaian.nama as type_name');
        $this->db->from('penilaian_pelamar');
        $this->db->join('penilaian', 'penilaian.id = penilaian_pelamar.id_penilaian', 'left');
        $this->db->join('jenis_penilaian', 'jenis_penilaian.id = penilaian.id_jenis', 'left');
        $this->db->join('lamaran_pekerjaan', 'lamaran_pekerjaan.id = penilaian_pelamar.id_lamaran', 'left');
        $this->db->join('lowongan_pekerjaan', 'lowongan_pekerjaan.id = lamaran_pekerjaan.id_pekerjaan', 'left');
        $this->db->where('penilaian_pelamar.id_pelamar', $user_id);
        $this->db->order_by('penilaian_pelamar.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Tambah penilaian pelamar
    public function tambah_penilaian_pelamar($data) {
        $this->db->insert('penilaian_pelamar', $data);
        return $this->db->insert_id();
    }

    // Perbarui status penilaian pelamar
    public function perbarui_status_penilaian_pelamar($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('penilaian_pelamar', array('status' => $status, 'waktu_selesai' => date('Y-m-d H:i:s')));
    }

    // Tambah jawaban pelamar
    public function tambah_jawaban_pelamar($data) {
        $this->db->insert('jawaban_pelamar', $data);
        return $this->db->insert_id();
    }

    // Dapatkan jawaban pelamar
    public function dapatkan_jawaban_pelamar($applicant_assessment_id) {
        $this->db->select('jawaban_pelamar.*, soal.teks_soal as question_text, soal.jenis_soal as question_type, soal.poin as points');
        $this->db->from('jawaban_pelamar');
        $this->db->join('soal', 'soal.id = jawaban_pelamar.id_soal', 'left');
        $this->db->where('jawaban_pelamar.id_penilaian_pelamar', $applicant_assessment_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Hitung skor penilaian pelamar
    public function hitung_skor_penilaian_pelamar($applicant_assessment_id) {
        $total_points = 0;
        $earned_points = 0;

        // Dapatkan semua jawaban pelamar
        $this->db->select('jawaban_pelamar.*, soal.poin as points, pilihan_soal.benar as is_correct');
        $this->db->from('jawaban_pelamar');
        $this->db->join('soal', 'soal.id = jawaban_pelamar.id_soal', 'left');
        $this->db->join('pilihan_soal', 'pilihan_soal.id = jawaban_pelamar.id_pilihan_terpilih', 'left');
        $this->db->where('jawaban_pelamar.id_penilaian_pelamar', $applicant_assessment_id);
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
        $this->db->update('penilaian_pelamar', array('nilai' => $score));

        return $score;
    }

    // Tetapkan penilaian ke lowongan
    public function tetapkan_penilaian_ke_lowongan($job_id, $assessment_id) {
        $data = array(
            'id_pekerjaan' => $job_id,
            'id_penilaian' => $assessment_id
        );
        return $this->db->insert('penilaian_pekerjaan', $data);
    }

    // Hapus penilaian dari lowongan
    public function hapus_penilaian_dari_lowongan($job_id, $assessment_id) {
        $this->db->where('id_pekerjaan', $job_id);
        $this->db->where('id_penilaian', $assessment_id);
        return $this->db->delete('penilaian_pekerjaan');
    }

    // Cek apakah penilaian sudah digunakan oleh pelamar
    public function cek_penilaian_digunakan($assessment_id) {
        $this->db->where('id_penilaian', $assessment_id);
        $query = $this->db->get('penilaian_pelamar');
        return ($query->num_rows() > 0);
    }

    // Dapatkan lowongan yang terkait dengan penilaian
    public function dapatkan_lowongan_penilaian($assessment_id) {
        $this->db->select('penilaian_pekerjaan.*, lowongan_pekerjaan.judul as job_title');
        $this->db->from('penilaian_pekerjaan');
        $this->db->join('lowongan_pekerjaan', 'lowongan_pekerjaan.id = penilaian_pekerjaan.id_pekerjaan', 'left');
        $this->db->where('penilaian_pekerjaan.id_penilaian', $assessment_id);
        $query = $this->db->get();
        return $query->row();
    }

    // Hitung jumlah soal dalam penilaian
    public function hitung_soal_penilaian($assessment_id) {
        $this->db->where('id_penilaian', $assessment_id);
        return $this->db->count_all_results('soal');
    }

    // Hitung jumlah pelamar yang ditugaskan penilaian
    public function hitung_pelamar_penilaian($assessment_id) {
        $this->db->where('id_penilaian', $assessment_id);
        return $this->db->count_all_results('penilaian_pelamar');
    }

    // Hitung jumlah pelamar yang telah menyelesaikan penilaian
    public function hitung_penyelesaian_penilaian($assessment_id) {
        $this->db->where('id_penilaian', $assessment_id);
        $this->db->where('status', 'selesai');
        return $this->db->count_all_results('penilaian_pelamar');
    }

    // Dapatkan penilaian aktif
    public function dapatkan_penilaian_aktif() {
        $this->db->select('penilaian.*, jenis_penilaian.nama as type_name');
        $this->db->from('penilaian');
        $this->db->join('jenis_penilaian', 'jenis_penilaian.id = penilaian.id_jenis', 'left');
        $this->db->where('penilaian.aktif', 1);
        $this->db->order_by('penilaian.judul', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan penilaian untuk lowongan tertentu
    public function dapatkan_penilaian_lowongan($job_id) {
        $this->db->select('penilaian_pekerjaan.*, penilaian.judul as assessment_title, jenis_penilaian.nama as type_name');
        $this->db->from('penilaian_pekerjaan');
        $this->db->join('penilaian', 'penilaian.id = penilaian_pekerjaan.id_penilaian', 'left');
        $this->db->join('jenis_penilaian', 'jenis_penilaian.id = penilaian.id_jenis', 'left');
        $this->db->where('penilaian_pekerjaan.id_pekerjaan', $job_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Hapus semua penilaian dari lowongan
    public function hapus_semua_penilaian_lowongan($job_id) {
        $this->db->where('id_pekerjaan', $job_id);
        return $this->db->delete('penilaian_pekerjaan');
    }

    // Cek apakah penilaian sudah ditetapkan ke pelamar
    public function cek_penilaian_sudah_ditetapkan($application_id, $assessment_id) {
        $this->db->where('id_lamaran', $application_id);
        $this->db->where('id_penilaian', $assessment_id);
        $query = $this->db->get('penilaian_pelamar');
        return ($query->num_rows() > 0);
    }

    // Hitung jumlah penilaian yang ditetapkan ke pelamar
    public function hitung_penilaian_pelamar($application_id) {
        $this->db->where('id_lamaran', $application_id);
        return $this->db->count_all_results('penilaian_pelamar');
    }

    // Hitung jumlah penilaian yang telah diselesaikan oleh pelamar
    public function hitung_penilaian_selesai($application_id) {
        $this->db->where('id_lamaran', $application_id);
        $this->db->where('status', 'selesai');
        return $this->db->count_all_results('penilaian_pelamar');
    }

    // Hitung total penilaian
    public function hitung_penilaian() {
        return $this->db->count_all('penilaian');
    }

    // Hitung penilaian yang telah diselesaikan
    public function hitung_penilaian_selesai_semua() {
        $this->db->where('status', 'selesai');
        return $this->db->count_all_results('penilaian_pelamar');
    }

    // Dapatkan statistik penilaian berdasarkan bulan
    public function dapatkan_statistik_penilaian_bulanan($year) {
        $this->db->select('MONTH(dibuat_pada) as month, COUNT(*) as count');
        $this->db->from('penilaian_pelamar');
        $this->db->where('YEAR(dibuat_pada)', $year);
        $this->db->group_by('MONTH(dibuat_pada)');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan statistik skor penilaian
    public function dapatkan_statistik_skor_penilaian() {
        $this->db->select('FLOOR(nilai/10)*10 as score_range, COUNT(*) as count');
        $this->db->from('penilaian_pelamar');
        $this->db->where('status', 'selesai');
        $this->db->group_by('FLOOR(nilai/10)');
        $this->db->order_by('score_range', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan hasil penilaian
    public function dapatkan_hasil_penilaian($assessment_id) {
        $this->db->select('penilaian_pelamar.*, pengguna.nama_lengkap as full_name, pengguna.email, lamaran_pekerjaan.id as application_id, lowongan_pekerjaan.judul as job_title');
        $this->db->from('penilaian_pelamar');
        $this->db->join('lamaran_pekerjaan', 'lamaran_pekerjaan.id = penilaian_pelamar.id_lamaran', 'left');
        $this->db->join('pengguna', 'pengguna.id = lamaran_pekerjaan.id_pelamar', 'left');
        $this->db->join('lowongan_pekerjaan', 'lowongan_pekerjaan.id = lamaran_pekerjaan.id_pekerjaan', 'left');
        $this->db->where('penilaian_pelamar.id_penilaian', $assessment_id);
        $this->db->order_by('penilaian_pelamar.nilai', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan rata-rata skor penilaian
    public function dapatkan_rata_rata_skor($assessment_id) {
        $this->db->select_avg('nilai', 'score');
        $this->db->from('penilaian_pelamar');
        $this->db->where('id_penilaian', $assessment_id);
        $this->db->where('status', 'selesai');
        $query = $this->db->get();
        $result = $query->row();
        return $result->score ? round($result->score) : 0;
    }

    // Dapatkan pelamar penilaian (untuk tampilan edit)
    public function dapatkan_pelamar_penilaian($assessment_id, $limit = null) {
        $this->db->select('penilaian_pelamar.*, pengguna.nama_lengkap as applicant_name, pengguna.email as applicant_email, lamaran_pekerjaan.id as application_id, lowongan_pekerjaan.judul as job_title');
        $this->db->from('penilaian_pelamar');
        $this->db->join('lamaran_pekerjaan', 'lamaran_pekerjaan.id = penilaian_pelamar.id_lamaran', 'left');
        $this->db->join('pengguna', 'pengguna.id = lamaran_pekerjaan.id_pelamar', 'left');
        $this->db->join('lowongan_pekerjaan', 'lowongan_pekerjaan.id = lamaran_pekerjaan.id_pekerjaan', 'left');
        $this->db->where('penilaian_pelamar.id_penilaian', $assessment_id);
        $this->db->order_by('penilaian_pelamar.id', 'DESC');

        if ($limit !== null) {
            $this->db->limit($limit);
        }

        $query = $this->db->get();
        return $query->result();
    }
}

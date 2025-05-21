<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Pelamar extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Dapatkan profil pelamar berdasarkan user ID
    public function dapatkan_profil($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('applicant_profiles');
        return $query->row();
    }

    // Buat profil kosong untuk pelamar baru
    public function buat_profil($user_id) {
        $data = array(
            'user_id' => $user_id
        );
        return $this->db->insert('applicant_profiles', $data);
    }

    // Tambah profil pelamar dengan data
    public function tambah_profil($data) {
        return $this->db->insert('applicant_profiles', $data);
    }

    // Perbarui profil pelamar
    public function perbarui_profil($user_id, $data) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('applicant_profiles', $data);
    }

    // Hapus profil pelamar
    public function hapus_profil($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->delete('applicant_profiles');
    }

    // Dapatkan persentase kelengkapan profil
    public function dapatkan_persentase_kelengkapan_profil($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('applicant_profiles');
        $profile = $query->row();
        
        if (!$profile) {
            return 0;
        }
        
        $total_fields = 7; // Jumlah field yang dihitung (date_of_birth, gender, education, experience, skills, resume, linkedin_url)
        $filled_fields = 0;
        
        if (!empty($profile->date_of_birth)) $filled_fields++;
        if (!empty($profile->gender)) $filled_fields++;
        if (!empty($profile->education)) $filled_fields++;
        if (!empty($profile->experience)) $filled_fields++;
        if (!empty($profile->skills)) $filled_fields++;
        if (!empty($profile->resume)) $filled_fields++;
        if (!empty($profile->linkedin_url)) $filled_fields++;
        
        return round(($filled_fields / $total_fields) * 100);
    }

    // Dapatkan pelamar berdasarkan skills
    public function dapatkan_pelamar_berdasarkan_skills($skills) {
        $skills_array = explode(',', $skills);
        $skills_array = array_map('trim', $skills_array);
        
        $this->db->select('applicant_profiles.*, users.full_name, users.email, users.profile_picture');
        $this->db->from('applicant_profiles');
        $this->db->join('users', 'users.id = applicant_profiles.user_id', 'left');
        
        $this->db->group_start();
        foreach ($skills_array as $skill) {
            $this->db->or_like('applicant_profiles.skills', $skill);
        }
        $this->db->group_end();
        
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan pelamar berdasarkan pendidikan
    public function dapatkan_pelamar_berdasarkan_pendidikan($education) {
        $this->db->select('applicant_profiles.*, users.full_name, users.email, users.profile_picture');
        $this->db->from('applicant_profiles');
        $this->db->join('users', 'users.id = applicant_profiles.user_id', 'left');
        $this->db->like('applicant_profiles.education', $education);
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan pelamar berdasarkan pengalaman
    public function dapatkan_pelamar_berdasarkan_pengalaman($experience) {
        $this->db->select('applicant_profiles.*, users.full_name, users.email, users.profile_picture');
        $this->db->from('applicant_profiles');
        $this->db->join('users', 'users.id = applicant_profiles.user_id', 'left');
        $this->db->like('applicant_profiles.experience', $experience);
        $query = $this->db->get();
        return $query->result();
    }

    // Cari pelamar
    public function cari_pelamar($keyword) {
        $this->db->select('applicant_profiles.*, users.full_name, users.email, users.profile_picture');
        $this->db->from('applicant_profiles');
        $this->db->join('users', 'users.id = applicant_profiles.user_id', 'left');
        $this->db->group_start();
        $this->db->like('users.full_name', $keyword);
        $this->db->or_like('users.email', $keyword);
        $this->db->or_like('applicant_profiles.skills', $keyword);
        $this->db->or_like('applicant_profiles.education', $keyword);
        $this->db->or_like('applicant_profiles.experience', $keyword);
        $this->db->group_end();
        $query = $this->db->get();
        return $query->result();
    }

    // Dapatkan pelamar dengan paginasi
    public function dapatkan_pelamar_paginasi($limit, $start) {
        $this->db->select('applicant_profiles.*, users.full_name, users.email, users.profile_picture, users.status');
        $this->db->from('applicant_profiles');
        $this->db->join('users', 'users.id = applicant_profiles.user_id', 'left');
        $this->db->order_by('users.id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    // Hitung total pelamar
    public function hitung_pelamar() {
        $this->db->from('applicant_profiles');
        $this->db->join('users', 'users.id = applicant_profiles.user_id', 'left');
        return $this->db->count_all_results();
    }
}

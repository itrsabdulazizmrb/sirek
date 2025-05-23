<?php
/**
 * Script Migrasi Data dari Database Bahasa Inggris ke Database Bahasa Indonesia
 * 
 * Gunakan script ini untuk memindahkan data dari database lama (bahasa Inggris)
 * ke database baru (bahasa Indonesia) sesuai dengan struktur di sirek_db_id.sql
 */

// Konfigurasi database
$config = [
    'old_db' => [
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'sirek_db'
    ],
    'new_db' => [
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'sirek_db_id'
    ]
];

// Koneksi ke database lama
$old_db = new mysqli(
    $config['old_db']['hostname'],
    $config['old_db']['username'],
    $config['old_db']['password'],
    $config['old_db']['database']
);

// Koneksi ke database baru
$new_db = new mysqli(
    $config['new_db']['hostname'],
    $config['new_db']['username'],
    $config['new_db']['password'],
    $config['new_db']['database']
);

// Cek koneksi
if ($old_db->connect_error) {
    die("Koneksi ke database lama gagal: " . $old_db->connect_error);
}

if ($new_db->connect_error) {
    die("Koneksi ke database baru gagal: " . $new_db->connect_error);
}

echo "Koneksi ke database berhasil.\n";

// Fungsi untuk escape string
function escape($db, $string) {
    return $db->real_escape_string($string);
}

// Fungsi untuk migrasi data
function migrasi_data($old_db, $new_db, $old_table, $new_table, $mapping) {
    echo "Migrasi data dari tabel $old_table ke $new_table...\n";
    
    // Ambil data dari tabel lama
    $query = $old_db->query("SELECT * FROM $old_table");
    
    if (!$query) {
        echo "Error: " . $old_db->error . "\n";
        return false;
    }
    
    $count = 0;
    
    // Proses setiap baris data
    while ($row = $query->fetch_assoc()) {
        $columns = [];
        $values = [];
        
        // Mapping kolom
        foreach ($mapping as $old_column => $new_column) {
            if (isset($row[$old_column])) {
                $columns[] = $new_column;
                $values[] = "'" . escape($new_db, $row[$old_column]) . "'";
            }
        }
        
        // Buat query insert
        $sql = "INSERT INTO $new_table (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")";
        
        // Eksekusi query
        if ($new_db->query($sql)) {
            $count++;
        } else {
            echo "Error: " . $new_db->error . "\n";
            echo "Query: " . $sql . "\n";
        }
    }
    
    echo "Berhasil memindahkan $count data dari $old_table ke $new_table.\n";
    return true;
}

// Mapping tabel users ke pelamar
$users_mapping = [
    'id' => 'id',
    'username' => 'nama_pengguna',
    'email' => 'email',
    'password' => 'password',
    'role' => 'role',
    'full_name' => 'nama_lengkap',
    'phone' => 'telepon',
    'address' => 'alamat',
    'profile_picture' => 'foto_profil',
    'created_at' => 'dibuat_pada',
    'updated_at' => 'diperbarui_pada',
    'last_login' => 'login_terakhir',
    'status' => 'status'
];

// Mapping tabel job_categories ke kategori_pekerjaan
$job_categories_mapping = [
    'id' => 'id',
    'name' => 'nama',
    'description' => 'deskripsi',
    'created_at' => 'dibuat_pada',
    'updated_at' => 'diperbarui_pada'
];

// Mapping tabel job_postings ke lowongan_pekerjaan
$job_postings_mapping = [
    'id' => 'id',
    'title' => 'judul',
    'category_id' => 'id_kategori',
    'description' => 'deskripsi',
    'requirements' => 'persyaratan',
    'responsibilities' => 'tanggung_jawab',
    'location' => 'lokasi',
    'job_type' => 'jenis_pekerjaan',
    'salary_range' => 'rentang_gaji',
    'deadline' => 'batas_waktu',
    'vacancies' => 'jumlah_lowongan',
    'featured' => 'unggulan',
    'status' => 'status',
    'created_by' => 'dibuat_oleh',
    'created_at' => 'dibuat_pada',
    'updated_at' => 'diperbarui_pada'
];

// Mapping tabel job_applications ke lamaran_pekerjaan
$job_applications_mapping = [
    'id' => 'id',
    'job_id' => 'id_pekerjaan',
    'applicant_id' => 'id_pelamar',
    'cover_letter' => 'surat_lamaran',
    'resume' => 'resume',
    'status' => 'status',
    'application_date' => 'tanggal_lamaran',
    'admin_notes' => 'catatan_admin',
    'updated_at' => 'diperbarui_pada'
];

// Mapping tabel assessment_types ke jenis_penilaian
$assessment_types_mapping = [
    'id' => 'id',
    'name' => 'nama',
    'description' => 'deskripsi',
    'created_at' => 'dibuat_pada',
    'updated_at' => 'diperbarui_pada'
];

// Mapping tabel assessments ke penilaian
$assessments_mapping = [
    'id' => 'id',
    'title' => 'judul',
    'type_id' => 'id_jenis',
    'description' => 'deskripsi',
    'instructions' => 'instruksi',
    'time_limit' => 'batas_waktu',
    'passing_score' => 'nilai_lulus',
    'max_attempts' => 'maks_percobaan',
    'is_active' => 'aktif',
    'created_by' => 'dibuat_oleh',
    'created_at' => 'dibuat_pada',
    'updated_at' => 'diperbarui_pada'
];

// Mapping tabel questions ke soal
$questions_mapping = [
    'id' => 'id',
    'assessment_id' => 'id_penilaian',
    'question_text' => 'teks_soal',
    'question_type' => 'jenis_soal',
    'points' => 'poin',
    'created_at' => 'dibuat_pada',
    'updated_at' => 'diperbarui_pada'
];

// Mapping tabel question_options ke pilihan_soal
$question_options_mapping = [
    'id' => 'id',
    'question_id' => 'id_soal',
    'option_text' => 'teks_pilihan',
    'is_correct' => 'benar',
    'created_at' => 'dibuat_pada',
    'updated_at' => 'diperbarui_pada'
];

// Mapping tabel job_assessments ke penilaian_pekerjaan
$job_assessments_mapping = [
    'id' => 'id',
    'job_id' => 'id_pekerjaan',
    'assessment_id' => 'id_penilaian',
    'created_at' => 'dibuat_pada'
];

// Mapping tabel applicant_assessments ke penilaian_pelamar
$applicant_assessments_mapping = [
    'id' => 'id',
    'application_id' => 'id_lamaran',
    'assessment_id' => 'id_penilaian',
    'applicant_id' => 'id_pelamar',
    'status' => 'status',
    'score' => 'nilai',
    'start_time' => 'waktu_mulai',
    'completed_at' => 'waktu_selesai',
    'created_at' => 'dibuat_pada',
    'updated_at' => 'diperbarui_pada'
];

// Mapping tabel applicant_answers ke jawaban_pelamar
$applicant_answers_mapping = [
    'id' => 'id',
    'applicant_assessment_id' => 'id_penilaian_pelamar',
    'question_id' => 'id_soal',
    'selected_option_id' => 'id_pilihan_terpilih',
    'text_answer' => 'jawaban_teks',
    'created_at' => 'dibuat_pada'
];

// Jalankan migrasi data
migrasi_data($old_db, $new_db, 'users', 'pengguna', $users_mapping);
migrasi_data($old_db, $new_db, 'job_categories', 'kategori_pekerjaan', $job_categories_mapping);
migrasi_data($old_db, $new_db, 'job_postings', 'lowongan_pekerjaan', $job_postings_mapping);
migrasi_data($old_db, $new_db, 'job_applications', 'lamaran_pekerjaan', $job_applications_mapping);
migrasi_data($old_db, $new_db, 'assessment_types', 'jenis_penilaian', $assessment_types_mapping);
migrasi_data($old_db, $new_db, 'assessments', 'penilaian', $assessments_mapping);
migrasi_data($old_db, $new_db, 'questions', 'soal', $questions_mapping);
migrasi_data($old_db, $new_db, 'question_options', 'pilihan_soal', $question_options_mapping);
migrasi_data($old_db, $new_db, 'job_assessments', 'penilaian_pekerjaan', $job_assessments_mapping);
migrasi_data($old_db, $new_db, 'applicant_assessments', 'penilaian_pelamar', $applicant_assessments_mapping);
migrasi_data($old_db, $new_db, 'applicant_answers', 'jawaban_pelamar', $applicant_answers_mapping);

echo "Migrasi data selesai.\n";

// Tutup koneksi
$old_db->close();
$new_db->close();

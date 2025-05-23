# Panduan Implementasi Refactoring Database dari Bahasa Inggris ke Bahasa Indonesia

Dokumen ini berisi langkah-langkah detail untuk melakukan refactoring database dari bahasa Inggris ke bahasa Indonesia pada project SIREK.

## Persiapan

1. **Backup database dan kode sumber**
   ```bash
   # Backup database
   mysqldump -u root -p sirek_db > sirek_db_backup.sql
   
   # Backup kode sumber
   xcopy /E /I /H /Y C:\xampp\htdocs\sirek C:\xampp\htdocs\sirek_backup
   ```

2. **Buat database baru dengan struktur dari sirek_db_id.sql**
   ```bash
   # Buat database baru
   mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS sirek_db_id"
   
   # Import struktur database baru
   mysql -u root -p sirek_db_id < sirek_db_id.sql
   ```

## Migrasi Data

1. **Jalankan script migrasi data**
   ```bash
   # Jalankan script migrasi data
   php migrasi_data.php
   ```

   Script ini akan memindahkan data dari database lama (bahasa Inggris) ke database baru (bahasa Indonesia) sesuai dengan mapping yang telah ditentukan.

## Perubahan pada Konfigurasi Database

1. **Perbarui konfigurasi database di application/config/database.php**
   - Ubah nama database dari 'sirek_db' menjadi 'sirek_db_id'

   ```php
   $db['default'] = array(
       'dsn'   => '',
       'hostname' => 'localhost',
       'username' => 'root',
       'password' => '',
       'database' => 'sirek_db_id', // Ubah dari sirek_db menjadi sirek_db_id
       'dbdriver' => 'mysqli',
       'dbprefix' => '',
       'pconnect' => FALSE,
       'db_debug' => (ENVIRONMENT !== 'production'),
       'cache_on' => FALSE,
       'cachedir' => '',
       'char_set' => 'utf8',
       'dbcollat' => 'utf8_general_ci',
       'swap_pre' => '',
       'encrypt' => FALSE,
       'compress' => FALSE,
       'stricton' => FALSE,
       'failover' => array(),
       'save_queries' => TRUE
   );
   ```

## Perubahan pada Model

Berikut adalah daftar model yang perlu diubah dan contoh perubahan yang diperlukan:

### 1. Model_Pengguna.php
- Ubah semua referensi 'users' menjadi 'pelamar'
- Ubah semua referensi kolom seperti 'username' menjadi 'nama_pengguna', 'password' menjadi 'kata_sandi', dll.

Contoh:
```php
// Sebelum
public function dapatkan_pengguna_semua() {
    $this->db->order_by('id', 'DESC');
    $query = $this->db->get('users');
    return $query->result();
}

// Sesudah
public function dapatkan_pengguna_semua() {
    $this->db->order_by('id', 'DESC');
    $query = $this->db->get('pelamar');
    return $query->result();
}
```

### 2. Model_Lowongan.php
- Ubah semua referensi 'job_postings' menjadi 'lowongan_pekerjaan'
- Ubah semua referensi 'job_categories' menjadi 'kategori_pekerjaan'
- Ubah semua referensi kolom seperti 'title' menjadi 'judul', 'category_id' menjadi 'id_kategori', dll.

Contoh:
```php
// Sebelum
public function dapatkan_lowongan_semua() {
    $this->db->select('job_postings.*, job_categories.name as category_name, users.full_name as created_by_name');
    $this->db->from('job_postings');
    $this->db->join('job_categories', 'job_categories.id = job_postings.category_id', 'left');
    $this->db->join('users', 'users.id = job_postings.created_by', 'left');
    $this->db->order_by('job_postings.id', 'DESC');
    $query = $this->db->get();
    return $query->result();
}

// Sesudah
public function dapatkan_lowongan_semua() {
    $this->db->select('lowongan_pekerjaan.*, kategori_pekerjaan.nama as category_name, pelamar.nama_lengkap as created_by_name');
    $this->db->from('lowongan_pekerjaan');
    $this->db->join('kategori_pekerjaan', 'kategori_pekerjaan.id = lowongan_pekerjaan.id_kategori', 'left');
    $this->db->join('pelamar', 'pelamar.id = lowongan_pekerjaan.dibuat_oleh', 'left');
    $this->db->order_by('lowongan_pekerjaan.id', 'DESC');
    $query = $this->db->get();
    return $query->result();
}
```

### 3. Model_Lamaran.php
- Ubah semua referensi 'job_applications' menjadi 'lamaran_pekerjaan'
- Ubah semua referensi kolom seperti 'job_id' menjadi 'id_pekerjaan', 'applicant_id' menjadi 'id_pelamar', dll.

### 4. Model_Penilaian.php
- Ubah semua referensi 'assessments' menjadi 'penilaian'
- Ubah semua referensi 'assessment_types' menjadi 'jenis_penilaian'
- Ubah semua referensi 'questions' menjadi 'soal'
- Ubah semua referensi 'question_options' menjadi 'pilihan_soal'
- Ubah semua referensi 'applicant_assessments' menjadi 'penilaian_pelamar'
- Ubah semua referensi 'applicant_answers' menjadi 'jawaban_pelamar'

## Perubahan pada Controller

Berikut adalah daftar controller yang perlu diubah:

### 1. Admin.php
- Perbarui semua referensi ke data dari model
- Perbarui semua variabel yang menyimpan data dari database

### 2. Pelamar.php
- Perbarui semua referensi ke data dari model
- Perbarui semua variabel yang menyimpan data dari database

### 3. Auth.php
- Perbarui semua referensi ke tabel 'users' menjadi 'pelamar'
- Perbarui semua referensi ke kolom seperti 'username' menjadi 'nama_pengguna', 'password' menjadi 'kata_sandi', dll.

## Perubahan pada View

Berikut adalah daftar view yang perlu diubah:

### 1. admin/assessments/index.php
- Perbarui semua referensi ke data dari controller
- Perbarui semua referensi ke kolom seperti 'title' menjadi 'judul', dll.

### 2. admin/assessments/options.php
- Perbarui semua referensi ke data dari controller
- Perbarui semua referensi ke kolom seperti 'question_text' menjadi 'teks_soal', 'question_type' menjadi 'jenis_soal', dll.

## Pengujian

Setelah semua perubahan dilakukan, penting untuk melakukan pengujian menyeluruh untuk memastikan semua fungsionalitas tetap berjalan dengan baik:

1. **Uji login dan autentikasi**
   - Uji login dengan berbagai peran (admin, staff, pelamar)
   - Uji pendaftaran pengguna baru
   - Uji reset password

2. **Uji manajemen pengguna**
   - Uji melihat daftar pengguna
   - Uji menambah pengguna baru
   - Uji mengedit pengguna
   - Uji menghapus pengguna

3. **Uji manajemen lowongan**
   - Uji melihat daftar lowongan
   - Uji menambah lowongan baru
   - Uji mengedit lowongan
   - Uji menghapus lowongan

4. **Uji manajemen lamaran**
   - Uji melihat daftar lamaran
   - Uji menambah lamaran baru
   - Uji mengedit status lamaran
   - Uji menghapus lamaran

5. **Uji manajemen penilaian**
   - Uji melihat daftar penilaian
   - Uji menambah penilaian baru
   - Uji mengedit penilaian
   - Uji menghapus penilaian
   - Uji menambah soal ke penilaian
   - Uji mengikuti penilaian sebagai pelamar
   - Uji melihat hasil penilaian

## Rollback Plan

Jika terjadi masalah selama implementasi, berikut adalah langkah-langkah untuk melakukan rollback:

1. **Kembalikan konfigurasi database**
   - Ubah kembali nama database di application/config/database.php dari 'sirek_db_id' menjadi 'sirek_db'

2. **Kembalikan kode sumber**
   ```bash
   # Hapus kode sumber yang sudah diubah
   rmdir /S /Q C:\xampp\htdocs\sirek
   
   # Salin kembali kode sumber backup
   xcopy /E /I /H /Y C:\xampp\htdocs\sirek_backup C:\xampp\htdocs\sirek
   ```

3. **Kembalikan database**
   ```bash
   # Hapus database baru
   mysql -u root -p -e "DROP DATABASE IF EXISTS sirek_db_id"
   
   # Kembalikan database lama jika perlu
   mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS sirek_db"
   mysql -u root -p sirek_db < sirek_db_backup.sql
   ```

## Kesimpulan

Dengan mengikuti langkah-langkah di atas, Anda dapat melakukan refactoring database dari bahasa Inggris ke bahasa Indonesia dengan aman dan efektif. Pastikan untuk melakukan backup dan pengujian yang menyeluruh untuk memastikan semua fungsionalitas tetap berjalan dengan baik setelah refactoring.

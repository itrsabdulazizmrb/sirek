# Sistem CAT (Computer Adaptive Test) - Dokumentasi

## Deskripsi
Sistem CAT (Computer Adaptive Test) adalah implementasi ujian berbasis komputer yang menyerupai format ujian CPNS (Calon Pegawai Negeri Sipil) Indonesia. Sistem ini menyediakan interface ujian yang modern, aman, dan user-friendly dengan berbagai fitur keamanan dan navigasi yang canggih.

## Fitur Utama

### 1. Interface Layar Penuh (Full-Screen)
- **Mode Layar Penuh Otomatis**: Ujian secara otomatis masuk ke mode layar penuh
- **Pencegahan Keluar**: Sistem mencegah peserta keluar dari mode layar penuh
- **Peringatan Tab Switching**: Notifikasi jika peserta mencoba berpindah tab/aplikasi

### 2. Panel Navigasi Soal
- **Sidebar Navigasi**: Panel kiri menampilkan nomor soal yang dapat diklik
- **Status Visual Soal**:
  - **Hijau**: Soal sudah dijawab
  - **Kuning**: Soal ditandai ragu-ragu
  - **Biru**: Soal yang sedang aktif
  - **Putih**: Soal belum dijawab
- **Navigasi Langsung**: Klik nomor soal untuk langsung ke soal tersebut

### 3. Tampilan Soal Tunggal
- **Satu Soal Per Halaman**: Hanya menampilkan satu soal pada satu waktu
- **Navigasi Tombol**: Tombol "Sebelumnya" dan "Selanjutnya"
- **Auto-Save**: Jawaban tersimpan otomatis saat berpindah soal

### 4. Sistem Penandaan Ragu-ragu
- **Tombol "Tandai Ragu"**: Menandai soal yang masih diragukan
- **Visual Indicator**: Soal yang ditandai ragu tampil dengan warna kuning
- **Review Mudah**: Dapat dengan mudah kembali ke soal yang ditandai ragu

### 5. Pengacakan Soal
- **Randomisasi Per Peserta**: Setiap peserta mendapat urutan soal yang berbeda
- **Konsistensi Session**: Urutan tetap sama jika peserta refresh halaman
- **Database Storage**: Urutan soal disimpan di database untuk konsistensi

### 6. Keamanan Browser
- **Disable Right-Click**: Mencegah klik kanan
- **Disable Developer Tools**: Mencegah F12, Ctrl+Shift+I, dll.
- **Prevent Copy-Paste**: Mencegah Ctrl+C, Ctrl+V, Ctrl+U
- **Tab Switch Detection**: Deteksi perpindahan tab dengan peringatan

### 7. Timer dan Auto-Submit
- **Timer Real-time**: Menampilkan sisa waktu ujian
- **Warning 5 Menit**: Peringatan visual saat sisa waktu 5 menit
- **Auto-Submit**: Otomatis mengumpulkan jawaban saat waktu habis

## Struktur Database

### Tabel Baru
```sql
-- Tabel untuk menyimpan urutan soal per peserta
CREATE TABLE `urutan_soal_pelamar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_penilaian_pelamar` int NOT NULL,
  `id_soal` int NOT NULL,
  `urutan` int NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
```

### Kolom Baru
```sql
-- Tambahan kolom pada tabel penilaian
ALTER TABLE `penilaian` 
ADD COLUMN `acak_soal` TINYINT(1) NULL DEFAULT 0,
ADD COLUMN `mode_cat` TINYINT(1) NULL DEFAULT 0;

-- Tambahan kolom pada tabel jawaban_pelamar
ALTER TABLE `jawaban_pelamar` 
ADD COLUMN `ditandai_ragu` TINYINT(1) NULL DEFAULT 0;
```

## Cara Penggunaan

### Untuk Admin
1. **Membuat Penilaian CAT**:
   - Buka menu Admin > Penilaian > Tambah Penilaian
   - Centang "Mode CAT (Computer Adaptive Test)"
   - Opsional: Centang "Acak Urutan Soal" untuk randomisasi
   - Simpan penilaian

2. **Mengatur Soal**:
   - Tambahkan soal seperti biasa
   - Soal akan otomatis diacak jika fitur pengacakan diaktifkan

### Untuk Peserta
1. **Memulai Ujian**:
   - Klik "Ikuti Penilaian" pada penilaian yang menggunakan mode CAT
   - Sistem otomatis masuk ke interface CAT
   - Browser akan masuk mode layar penuh

2. **Mengerjakan Soal**:
   - Gunakan panel navigasi kiri untuk berpindah soal
   - Klik jawaban untuk memilih (pilihan ganda)
   - Gunakan tombol "Tandai Ragu" untuk soal yang diragukan
   - Jawaban tersimpan otomatis

3. **Menyelesaikan Ujian**:
   - Klik "Selesai" pada soal terakhir
   - Konfirmasi pengumpulan jawaban
   - Sistem akan mengumpulkan semua jawaban

## File yang Dimodifikasi/Ditambahkan

### Database
- `sirek_db_id.sql` - Schema database utama
- `update_cat_system.sql` - Script update untuk database existing

### Models
- `application/models/Model_Penilaian.php` - Tambahan method CAT

### Controllers
- `application/controllers/Pelamar.php` - Controller untuk interface CAT
- `application/controllers/Admin.php` - Update untuk pengaturan CAT

### Views
- `application/views/applicant/cat_penilaian.php` - Interface CAT utama
- `application/views/admin/penilaian/tambah.php` - Form tambah dengan opsi CAT
- `application/views/admin/penilaian/edit.php` - Form edit dengan opsi CAT

### Routes
- `pelamar/cat-penilaian/{assessment_id}/{application_id}/{question_number}` - Interface CAT
- `pelamar/simpan-jawaban-cat` - AJAX endpoint untuk menyimpan jawaban
- `pelamar/tandai-ragu-cat` - AJAX endpoint untuk menandai ragu
- `pelamar/dapatkan-status-navigasi-cat` - AJAX endpoint untuk status navigasi

## Keunggulan Sistem CAT

1. **User Experience Modern**: Interface yang clean dan intuitif
2. **Keamanan Tinggi**: Berbagai lapisan keamanan browser
3. **Fleksibilitas Navigasi**: Dapat berpindah soal dengan mudah
4. **Visual Feedback**: Status soal yang jelas dan informatif
5. **Auto-Save**: Tidak ada kehilangan data jawaban
6. **Responsive Design**: Bekerja baik di desktop dan tablet
7. **Konsistensi**: Pengalaman ujian yang sama untuk semua peserta

## Kompatibilitas
- **Browser**: Chrome, Firefox, Safari, Edge (versi terbaru)
- **Device**: Desktop, Laptop, Tablet (landscape mode)
- **Resolution**: Minimum 1024x768px

## Troubleshooting

### Masalah Umum
1. **Layar Penuh Tidak Aktif**: Pastikan browser mendukung Fullscreen API
2. **Timer Tidak Akurat**: Periksa koneksi internet dan sinkronisasi waktu
3. **Navigasi Tidak Update**: Refresh halaman atau periksa koneksi AJAX

### Maintenance
1. **Cleanup Data**: Hapus data `urutan_soal_pelamar` untuk ujian yang sudah selesai
2. **Performance**: Index database pada kolom yang sering diquery
3. **Backup**: Pastikan tabel baru masuk dalam backup rutin

## Pengembangan Lanjutan
- Implementasi adaptive scoring
- Integrasi dengan sistem proctoring
- Analytics dan reporting yang lebih detail
- Mobile app support
- Offline mode capability

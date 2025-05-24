# Fitur Upload dan Tampilan Gambar Soal Penilaian

## Deskripsi
Fitur ini memungkinkan admin untuk mengunggah gambar pada soal penilaian dan menampilkannya kepada pelamar saat mengikuti ujian. Fitur ini mendukung berbagai format gambar dan menyediakan preview serta lightbox untuk memperbesar gambar.

## Fitur Utama

### 1. Upload Gambar untuk Admin
- **Format yang didukung**: JPG, JPEG, PNG, GIF
- **Ukuran maksimal**: 4MB
- **Lokasi penyimpanan**: `uploads/gambar_soal/`
- **Preview real-time** saat upload
- **Validasi format dan ukuran file**
- **Opsi untuk menghapus gambar yang sudah diupload**

### 2. Tampilan Gambar untuk Pelamar
- **Tampilan responsif** di halaman ujian
- **Lightbox/modal** untuk memperbesar gambar
- **Loading fallback** jika gambar tidak ditemukan
- **Optimasi untuk mobile device**

### 3. Manajemen Gambar
- **Thumbnail preview** di daftar soal admin
- **Auto-cleanup** saat soal dihapus
- **Replace gambar** saat edit soal
- **Validasi dan error handling**

## Struktur Database

### Tabel `soal`
Ditambahkan kolom baru:
```sql
`gambar_soal` VARCHAR(255) NULL DEFAULT NULL
```

## File yang Dimodifikasi

### 1. Database Schema
- `sirek_db_id.sql` - Menambahkan kolom `gambar_soal`
- `update_gambar_soal.sql` - Script update untuk database existing

### 2. Model
- `application/models/Model_Penilaian.php`
  - Method `hapus_soal()` - Auto-delete gambar saat soal dihapus
  - Method `hapus_gambar_soal()` - Hapus gambar tanpa hapus soal

### 3. Controller
- `application/controllers/Admin.php`
  - Method `tambah_soal()` - Handle upload gambar saat tambah soal
  - Method `edit_question()` - Handle upload gambar saat edit soal
  - Method `delete_question()` - Hapus soal beserta gambar
  - Method `hapus_gambar_soal()` - AJAX endpoint untuk hapus gambar

### 4. Views - Admin
- `application/views/admin/penilaian/tambah_soal.php`
  - Form upload gambar dengan preview
  - Validasi client-side
  - Drag & drop interface

- `application/views/admin/penilaian/edit_soal.php` (Baru)
  - Form edit soal dengan manajemen gambar
  - Preview gambar existing
  - Opsi ganti atau hapus gambar

- `application/views/admin/penilaian/soal.php`
  - Kolom thumbnail gambar di tabel
  - Modal preview gambar
  - Responsive table layout

### 5. Views - Pelamar
- `application/views/applicant/ikuti_penilaian.php`
  - Tampilan gambar soal dengan lightbox
  - Responsive design
  - Loading fallback

### 6. Routes
- `application/config/routes.php`
  - Route untuk hapus gambar: `admin/hapus-gambar-soal/(:num)`

## Cara Penggunaan

### Untuk Admin

#### 1. Menambah Soal dengan Gambar
1. Masuk ke halaman "Kelola Soal Penilaian"
2. Klik "Tambah Soal"
3. Isi form soal seperti biasa
4. Di bagian "Gambar Soal (Opsional)", klik "Pilih gambar..."
5. Pilih file gambar (JPG, JPEG, PNG, GIF, max 4MB)
6. Preview akan muncul otomatis
7. Klik "Simpan Soal"

#### 2. Mengedit Soal dengan Gambar
1. Di daftar soal, klik dropdown "Aksi" → "Edit"
2. Jika soal sudah memiliki gambar, akan ditampilkan preview
3. Untuk mengganti gambar: pilih file baru di "Ganti Gambar Soal"
4. Untuk menghapus gambar: klik tombol "Hapus Gambar"
5. Klik "Perbarui Soal"

#### 3. Melihat Preview Gambar
- Di daftar soal, klik thumbnail gambar untuk memperbesar
- Modal akan menampilkan gambar dalam ukuran penuh

### Untuk Pelamar

#### 1. Melihat Soal dengan Gambar
1. Saat mengikuti ujian, gambar soal akan ditampilkan di atas teks soal
2. Klik gambar untuk memperbesar dalam modal
3. Gambar akan responsive dan menyesuaikan ukuran layar

## Validasi dan Error Handling

### Client-side Validation
- **Format file**: Hanya JPG, JPEG, PNG, GIF
- **Ukuran file**: Maksimal 4MB
- **Preview real-time**: Menampilkan gambar sebelum upload

### Server-side Validation
- **Upload library CodeIgniter**: Validasi format dan ukuran
- **Error messages**: Pesan error dalam bahasa Indonesia
- **Fallback handling**: Jika upload gagal, form tetap bisa disubmit tanpa gambar

### File Management
- **Auto-cleanup**: Gambar lama dihapus saat diganti atau soal dihapus
- **Unique filename**: Mencegah konflik nama file
- **Directory creation**: Otomatis membuat folder jika belum ada

## Keamanan

### Upload Security
- **Whitelist extension**: Hanya format gambar yang diizinkan
- **File size limit**: Mencegah upload file terlalu besar
- **Unique naming**: Mencegah file overwrite

### Access Control
- **Admin only**: Hanya admin yang bisa upload/hapus gambar
- **Session validation**: Validasi login dan role
- **CSRF protection**: Menggunakan form helper CodeIgniter

## Troubleshooting

### Masalah Umum

#### 1. Error "The upload path does not appear to be valid"
**Solusi:**
- Pastikan folder `uploads/gambar_soal/` ada dan writable (chmod 777)
- Jalankan script test: `test_upload_path.php` untuk verifikasi path
- Periksa FCPATH di `index.php` sudah benar
- Pastikan tidak ada typo di path upload

**Langkah perbaikan:**
```bash
# Buat folder jika belum ada
mkdir -p uploads/gambar_soal

# Set permission
chmod 777 uploads/gambar_soal

# Test dengan script
php test_upload_path.php
```

#### 2. Gambar tidak muncul di halaman
- Pastikan file gambar benar-benar ada di `uploads/gambar_soal/`
- Cek apakah file `.htaccess` ada di folder gambar_soal
- Periksa path base_url() di konfigurasi CodeIgniter
- Pastikan web server bisa serve file static

#### 3. Upload gagal dengan error lain
- Cek ukuran file (max 4MB)
- Pastikan format file didukung (JPG, JPEG, PNG, GIF)
- Periksa permission folder uploads dan subfolder
- Cek log error web server untuk detail

#### 4. Modal tidak berfungsi
- Pastikan Bootstrap JS sudah dimuat
- Cek console browser untuk error JavaScript
- Pastikan jQuery sudah dimuat sebelum Bootstrap

### Maintenance

#### 1. Cleanup File Orphan
Jika ada file gambar yang tidak terpakai, bisa dibersihkan dengan query:
```sql
SELECT gambar_soal FROM soal WHERE gambar_soal IS NOT NULL;
```
Bandingkan dengan file di folder `uploads/gambar_soal/`

#### 2. Backup Gambar
Pastikan folder `uploads/gambar_soal/` masuk dalam backup rutin sistem.

## Pengembangan Lanjutan

### Fitur yang Bisa Ditambahkan
1. **Multiple images per question**: Upload beberapa gambar untuk satu soal
2. **Image compression**: Otomatis kompres gambar untuk menghemat storage
3. **Image editing**: Crop, resize, rotate gambar langsung di interface
4. **Bulk upload**: Upload banyak gambar sekaligus
5. **Image gallery**: Galeri gambar yang bisa digunakan ulang
6. **Watermark**: Otomatis tambah watermark pada gambar

### Optimasi Performance
1. **Lazy loading**: Load gambar hanya saat dibutuhkan
2. **CDN integration**: Gunakan CDN untuk serve gambar
3. **Image caching**: Cache gambar di browser
4. **Thumbnail generation**: Generate thumbnail otomatis

## Changelog

### Version 1.0.0 (Current)
- ✅ Upload gambar pada form tambah soal
- ✅ Upload gambar pada form edit soal
- ✅ Hapus gambar individual
- ✅ Preview gambar di admin
- ✅ Tampilan gambar untuk pelamar
- ✅ Lightbox/modal untuk memperbesar gambar
- ✅ Responsive design
- ✅ Validasi format dan ukuran file
- ✅ Auto-cleanup saat hapus soal
- ✅ Thumbnail di daftar soal admin

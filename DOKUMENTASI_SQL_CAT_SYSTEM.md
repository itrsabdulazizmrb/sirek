# Dokumentasi SQL Script Sistem CAT

## Daftar File SQL

Berikut adalah semua file SQL yang telah dibuat untuk implementasi sistem CAT (Computer Adaptive Test):

### 1. `sql_update_cat_system_lengkap.sql`
**Tujuan**: Update database yang sudah ada dengan fitur CAT
**Kapan digunakan**: Ketika Anda sudah memiliki database SIREK dan ingin menambahkan fitur CAT

**Fitur**:
- ✅ Cek kolom sebelum menambahkan (tidak akan error jika sudah ada)
- ✅ Menambahkan kolom `acak_soal` dan `mode_cat` ke tabel `penilaian`
- ✅ Menambahkan kolom `ditandai_ragu` ke tabel `jawaban_pelamar`
- ✅ Membuat tabel baru `urutan_soal_pelamar`
- ✅ Menambahkan index untuk performa
- ✅ Insert data sample
- ✅ Validasi dan verifikasi instalasi

**Cara penggunaan**:
```sql
mysql -u root -p sirek_db < sql_update_cat_system_lengkap.sql
```

### 2. `sql_fresh_install_cat.sql`
**Tujuan**: Instalasi database baru dengan fitur CAT sudah terintegrasi
**Kapan digunakan**: Ketika Anda ingin membuat database SIREK baru dari awal

**Fitur**:
- ✅ Membuat database `sirek_db` jika belum ada
- ✅ Membuat semua tabel dengan fitur CAT sudah terintegrasi
- ✅ Foreign key constraints lengkap
- ✅ Index yang optimal
- ✅ Data sample untuk testing
- ✅ User admin default

**Cara penggunaan**:
```sql
mysql -u root -p < sql_fresh_install_cat.sql
```

### 3. `sql_rollback_cat_system.sql`
**Tujuan**: Menghapus semua perubahan sistem CAT (rollback)
**Kapan digunakan**: Ketika Anda ingin mengembalikan database ke kondisi sebelum CAT

**Fitur**:
- ⚠️ Backup data sebelum rollback (opsional)
- ❌ Menghapus tabel `urutan_soal_pelamar`
- ❌ Menghapus kolom CAT dari tabel yang ada
- ❌ Menghapus index CAT
- ✅ Verifikasi rollback
- ✅ Panduan file yang perlu dihapus manual

**Cara penggunaan**:
```sql
mysql -u root -p sirek_db < sql_rollback_cat_system.sql
```

## Struktur Database CAT

### Tabel Baru

#### `urutan_soal_pelamar`
Menyimpan urutan soal yang diacak untuk setiap peserta dalam mode CAT.

```sql
CREATE TABLE `urutan_soal_pelamar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_penilaian_pelamar` int NOT NULL,
  `id_soal` int NOT NULL,
  `urutan` int NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_pelamar_soal` (`id_penilaian_pelamar`, `id_soal`)
);
```

### Kolom Baru

#### Tabel `penilaian`
- `acak_soal` TINYINT(1) DEFAULT 0 - Aktifkan pengacakan urutan soal
- `mode_cat` TINYINT(1) DEFAULT 0 - Aktifkan mode CAT interface

#### Tabel `jawaban_pelamar`
- `ditandai_ragu` TINYINT(1) DEFAULT 0 - Tandai soal sebagai ragu-ragu

### Index Baru
- `idx_penilaian_mode_cat` pada `penilaian(mode_cat)`
- `idx_penilaian_acak_soal` pada `penilaian(acak_soal)`
- `idx_jawaban_ditandai_ragu` pada `jawaban_pelamar(ditandai_ragu)`
- `idx_jawaban_penilaian_soal` pada `jawaban_pelamar(id_penilaian_pelamar, id_soal)`

## Panduan Instalasi

### Skenario 1: Database Baru
Jika Anda belum memiliki database SIREK:

```bash
# 1. Jalankan instalasi fresh
mysql -u root -p < sql_fresh_install_cat.sql

# 2. Verifikasi instalasi
php test_cat_system.php

# 3. Import data sample jika diperlukan
# mysql -u root -p sirek_db < data_sample.sql
```

### Skenario 2: Database Existing
Jika Anda sudah memiliki database SIREK:

```bash
# 1. Backup database terlebih dahulu
mysqldump -u root -p sirek_db > backup_sebelum_cat.sql

# 2. Jalankan update script
mysql -u root -p sirek_db < sql_update_cat_system_lengkap.sql

# 3. Verifikasi instalasi
php test_cat_system.php
```

### Skenario 3: Rollback
Jika Anda ingin menghapus fitur CAT:

```bash
# 1. Backup data CAT jika diperlukan
mysqldump -u root -p sirek_db urutan_soal_pelamar > backup_data_cat.sql

# 2. Jalankan rollback script
mysql -u root -p sirek_db < sql_rollback_cat_system.sql

# 3. Hapus file-file CAT secara manual (lihat output script)
```

## Verifikasi Instalasi

Setelah menjalankan script SQL, gunakan file `test_cat_system.php` untuk memverifikasi:

```bash
php test_cat_system.php
```

Script ini akan mengecek:
- ✅ Struktur tabel dan kolom
- ✅ Foreign key constraints
- ✅ File controller dan model
- ✅ File view CAT
- ✅ Fungsi pengacakan soal

## Troubleshooting

### Error: Table already exists
**Solusi**: Gunakan `sql_update_cat_system_lengkap.sql` yang memiliki pengecekan `IF NOT EXISTS`

### Error: Column already exists
**Solusi**: Script update sudah menangani ini dengan pengecekan `INFORMATION_SCHEMA`

### Error: Foreign key constraint fails
**Solusi**: 
1. Pastikan tabel parent sudah ada
2. Jalankan `SET FOREIGN_KEY_CHECKS = 0;` sebelum script
3. Jalankan `SET FOREIGN_KEY_CHECKS = 1;` setelah script

### Error: Access denied
**Solusi**: Pastikan user MySQL memiliki privilege:
```sql
GRANT ALL PRIVILEGES ON sirek_db.* TO 'username'@'localhost';
FLUSH PRIVILEGES;
```

## Maintenance

### Cleanup Data Lama
```sql
-- Hapus urutan soal untuk ujian yang sudah selesai > 30 hari
DELETE FROM urutan_soal_pelamar 
WHERE id_penilaian_pelamar IN (
    SELECT id FROM penilaian_pelamar 
    WHERE status = 'selesai' 
    AND waktu_selesai < DATE_SUB(NOW(), INTERVAL 30 DAY)
);
```

### Optimasi Performa
```sql
-- Analyze table untuk optimasi query
ANALYZE TABLE urutan_soal_pelamar;
ANALYZE TABLE jawaban_pelamar;
ANALYZE TABLE penilaian;

-- Cek index usage
SHOW INDEX FROM urutan_soal_pelamar;
```

### Monitoring
```sql
-- Cek penggunaan mode CAT
SELECT 
    COUNT(*) as total_penilaian,
    SUM(mode_cat) as penilaian_cat,
    SUM(acak_soal) as penilaian_acak
FROM penilaian;

-- Cek data urutan soal
SELECT 
    COUNT(*) as total_urutan,
    COUNT(DISTINCT id_penilaian_pelamar) as peserta_unik
FROM urutan_soal_pelamar;
```

## Backup & Recovery

### Backup Sebelum Update
```bash
# Full backup
mysqldump -u root -p sirek_db > backup_full_$(date +%Y%m%d).sql

# Backup struktur saja
mysqldump -u root -p --no-data sirek_db > backup_structure_$(date +%Y%m%d).sql
```

### Recovery
```bash
# Restore full backup
mysql -u root -p sirek_db < backup_full_20241201.sql

# Restore struktur saja
mysql -u root -p sirek_db < backup_structure_20241201.sql
```

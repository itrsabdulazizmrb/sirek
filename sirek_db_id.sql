/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 100130 (10.1.30-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : sirek_db

 Target Server Type    : MySQL
 Target Server Version : 100130 (10.1.30-MariaDB)
 File Encoding         : 65001

 Date: 23/05/2025 16:33:03
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Struktur tabel untuk jawaban_pelamar
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pelamar`;
CREATE TABLE `jawaban_pelamar`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_penilaian_pelamar` int NOT NULL,
  `id_pertanyaan` int NOT NULL,
  `teks_jawaban` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `id_pilihan_terpilih` int NULL DEFAULT NULL,
  `unggah_file` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nilai` int NULL DEFAULT NULL,
  `dinilai_oleh` int NULL DEFAULT NULL,
  `tanggal_dinilai` timestamp NULL DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_penilaian_pelamar`(`id_penilaian_pelamar` ASC) USING BTREE,
  INDEX `id_pertanyaan`(`id_pertanyaan` ASC) USING BTREE,
  INDEX `id_pilihan_terpilih`(`id_pilihan_terpilih` ASC) USING BTREE,
  INDEX `dinilai_oleh`(`dinilai_oleh` ASC) USING BTREE,
  CONSTRAINT `jawaban_pelamar_ibfk_1` FOREIGN KEY (`id_penilaian_pelamar`) REFERENCES `penilaian_pelamar` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `jawaban_pelamar_ibfk_2` FOREIGN KEY (`id_pertanyaan`) REFERENCES `pertanyaan` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `jawaban_pelamar_ibfk_3` FOREIGN KEY (`id_pilihan_terpilih`) REFERENCES `pilihan_pertanyaan` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `jawaban_pelamar_ibfk_4` FOREIGN KEY (`dinilai_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk penilaian_pelamar
-- ----------------------------
DROP TABLE IF EXISTS `penilaian_pelamar`;
CREATE TABLE `penilaian_pelamar`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_lamaran` int NOT NULL,
  `id_penilaian` int NOT NULL,
  `waktu_mulai` timestamp NULL DEFAULT NULL,
  `waktu_selesai` timestamp NULL DEFAULT NULL,
  `nilai` int NULL DEFAULT NULL,
  `status` enum('belum_mulai','sedang_berlangsung','selesai','sudah_dinilai') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'belum_mulai',
  `ditugaskan_pada` timestamp NULL DEFAULT NULL,
  `ditugaskan_oleh` int NULL DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_lamaran`(`id_lamaran` ASC) USING BTREE,
  INDEX `id_penilaian`(`id_penilaian` ASC) USING BTREE,
  INDEX `ditugaskan_oleh`(`ditugaskan_oleh` ASC) USING BTREE,
  CONSTRAINT `penilaian_pelamar_ibfk_1` FOREIGN KEY (`id_lamaran`) REFERENCES `lamaran_pekerjaan` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `penilaian_pelamar_ibfk_2` FOREIGN KEY (`id_penilaian`) REFERENCES `penilaian` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `penilaian_pelamar_ibfk_3` FOREIGN KEY (`ditugaskan_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk profil_pelamar
-- ----------------------------
DROP TABLE IF EXISTS `profil_pelamar`;
CREATE TABLE `profil_pelamar`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int NOT NULL,
  `tanggal_lahir` date NULL DEFAULT NULL,
  `jenis_kelamin` enum('laki-laki','perempuan','lainnya') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pendidikan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `pengalaman` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `keahlian` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `cv` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `surat_lamaran` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `url_linkedin` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `url_portofolio` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_pengguna`(`id_pengguna` ASC) USING BTREE,
  CONSTRAINT `profil_pelamar_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk jenis_penilaian
-- ----------------------------
DROP TABLE IF EXISTS `jenis_penilaian`;
CREATE TABLE `jenis_penilaian`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `deskripsi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk penilaian
-- ----------------------------
DROP TABLE IF EXISTS `penilaian`;
CREATE TABLE `penilaian`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_jenis` int NOT NULL,
  `deskripsi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `petunjuk` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `batas_waktu` int NULL DEFAULT NULL,
  `nilai_lulus` int NULL DEFAULT NULL,
  `aktif` tinyint(1) NULL DEFAULT 1,
  `maksimal_percobaan` int NULL DEFAULT 1,
  `dibuat_oleh` int NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_jenis`(`id_jenis` ASC) USING BTREE,
  INDEX `dibuat_oleh`(`dibuat_oleh` ASC) USING BTREE,
  CONSTRAINT `penilaian_ibfk_1` FOREIGN KEY (`id_jenis`) REFERENCES `jenis_penilaian` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `penilaian_ibfk_2` FOREIGN KEY (`dibuat_oleh`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk kategori_blog
-- ----------------------------
DROP TABLE IF EXISTS `kategori_blog`;
CREATE TABLE `kategori_blog`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `deskripsi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `slug`(`slug` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk kategori_post_blog
-- ----------------------------
DROP TABLE IF EXISTS `kategori_post_blog`;
CREATE TABLE `kategori_post_blog`  (
  `id_post` int NOT NULL,
  `id_kategori` int NOT NULL,
  PRIMARY KEY (`id_post`, `id_kategori`) USING BTREE,
  INDEX `id_kategori`(`id_kategori` ASC) USING BTREE,
  CONSTRAINT `kategori_post_blog_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `post_blog` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `kategori_post_blog_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_blog` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk post_blog
-- ----------------------------
DROP TABLE IF EXISTS `post_blog`;
CREATE TABLE `post_blog`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `konten` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gambar_utama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` enum('dipublikasi','draft') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'draft',
  `tampilan` int NULL DEFAULT 0,
  `id_penulis` int NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `slug`(`slug` ASC) USING BTREE,
  INDEX `id_penulis`(`id_penulis` ASC) USING BTREE,
  CONSTRAINT `post_blog_ibfk_1` FOREIGN KEY (`id_penulis`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk lamaran_pekerjaan
-- ----------------------------
DROP TABLE IF EXISTS `lamaran_pekerjaan`;
CREATE TABLE `lamaran_pekerjaan`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pekerjaan` int NOT NULL,
  `id_pelamar` int NOT NULL,
  `surat_lamaran` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `cv` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` enum('menunggu','direview','wawancara','interview','seleksi','diterima','ditolak') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'menunggu',
  `tanggal_lamaran` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `catatan_admin` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_pekerjaan`(`id_pekerjaan` ASC) USING BTREE,
  INDEX `id_pelamar`(`id_pelamar` ASC) USING BTREE,
  CONSTRAINT `lamaran_pekerjaan_ibfk_1` FOREIGN KEY (`id_pekerjaan`) REFERENCES `lowongan_pekerjaan` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `lamaran_pekerjaan_ibfk_2` FOREIGN KEY (`id_pelamar`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk penilaian_pekerjaan
-- ----------------------------
DROP TABLE IF EXISTS `penilaian_pekerjaan`;
CREATE TABLE `penilaian_pekerjaan`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pekerjaan` int NOT NULL,
  `id_penilaian` int NOT NULL,
  `wajib` tinyint(1) NULL DEFAULT 1,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_pekerjaan`(`id_pekerjaan` ASC) USING BTREE,
  INDEX `id_penilaian`(`id_penilaian` ASC) USING BTREE,
  CONSTRAINT `penilaian_pekerjaan_ibfk_1` FOREIGN KEY (`id_pekerjaan`) REFERENCES `lowongan_pekerjaan` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `penilaian_pekerjaan_ibfk_2` FOREIGN KEY (`id_penilaian`) REFERENCES `penilaian` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk kategori_pekerjaan
-- ----------------------------
DROP TABLE IF EXISTS `kategori_pekerjaan`;
CREATE TABLE `kategori_pekerjaan`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `deskripsi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk lowongan_pekerjaan
-- ----------------------------
DROP TABLE IF EXISTS `lowongan_pekerjaan`;
CREATE TABLE `lowongan_pekerjaan`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_kategori` int NULL DEFAULT NULL,
  `deskripsi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `persyaratan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `tanggung_jawab` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `lokasi` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jenis_pekerjaan` enum('penuh_waktu','paruh_waktu','kontrak','magang') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `rentang_gaji` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `batas_waktu` date NULL DEFAULT NULL,
  `jumlah_lowongan` int NULL DEFAULT 1,
  `unggulan` tinyint(1) NULL DEFAULT 0,
  `status` enum('aktif','ditutup','draft') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'draft',
  `dibuat_oleh` int NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_kategori`(`id_kategori` ASC) USING BTREE,
  INDEX `dibuat_oleh`(`dibuat_oleh` ASC) USING BTREE,
  CONSTRAINT `lowongan_pekerjaan_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_pekerjaan` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `lowongan_pekerjaan_ibfk_2` FOREIGN KEY (`dibuat_oleh`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk notifikasi
-- ----------------------------
DROP TABLE IF EXISTS `notifikasi`;
CREATE TABLE `notifikasi`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int NOT NULL,
  `judul` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pesan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `sudah_dibaca` tinyint(1) NULL DEFAULT 0,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_pengguna`(`id_pengguna` ASC) USING BTREE,
  CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk pilihan_pertanyaan
-- ----------------------------
DROP TABLE IF EXISTS `pilihan_pertanyaan`;
CREATE TABLE `pilihan_pertanyaan`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pertanyaan` int NOT NULL,
  `teks_pilihan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `benar` tinyint(1) NULL DEFAULT 0,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_pertanyaan`(`id_pertanyaan` ASC) USING BTREE,
  CONSTRAINT `pilihan_pertanyaan_ibfk_1` FOREIGN KEY (`id_pertanyaan`) REFERENCES `pertanyaan` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk pertanyaan
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan`;
CREATE TABLE `pertanyaan`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_penilaian` int NOT NULL,
  `teks_pertanyaan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jenis_pertanyaan` enum('pilihan_ganda','benar_salah','esai','unggah_file') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `poin` int NULL DEFAULT 1,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_penilaian`(`id_penilaian` ASC) USING BTREE,
  CONSTRAINT `pertanyaan_ibfk_1` FOREIGN KEY (`id_penilaian`) REFERENCES `penilaian` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur tabel untuk pengguna
-- ----------------------------
DROP TABLE IF EXISTS `pengguna`;
CREATE TABLE `pengguna`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_pengguna` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kata_sandi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `peran` enum('admin','staff','pelamar') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `telepon` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alamat` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `foto_profil` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `login_terakhir` timestamp NULL DEFAULT NULL,
  `status` enum('aktif','nonaktif','diblokir') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'aktif',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `nama_pengguna`(`nama_pengguna` ASC) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Struktur view untuk pekerjaan
-- ----------------------------
DROP VIEW IF EXISTS `pekerjaan`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `pekerjaan` AS SELECT 
    id,
    judul,
    id_kategori,
    deskripsi,
    persyaratan,
    tanggung_jawab,
    lokasi,
    jenis_pekerjaan,
    rentang_gaji,
    batas_waktu,
    jumlah_lowongan,
    unggulan,
    status,
    dibuat_oleh,
    dibuat_pada,
    diperbarui_pada
FROM lowongan_pekerjaan ;

SET FOREIGN_KEY_CHECKS = 1; 
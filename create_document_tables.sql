-- Create dokumen_lowongan table (job document requirements)
CREATE TABLE `dokumen_lowongan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lowongan` int(11) NOT NULL,
  `jenis_dokumen` varchar(50) NOT NULL,
  `nama_dokumen` varchar(100) NOT NULL,
  `wajib` tinyint(1) NOT NULL DEFAULT 1,
  `format_diizinkan` varchar(100) NOT NULL DEFAULT 'pdf|doc|docx|jpg|jpeg|png',
  `ukuran_maksimal` int(11) NOT NULL DEFAULT 2048,
  `deskripsi` text DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `diperbarui_pada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_lowongan` (`id_lowongan`),
  CONSTRAINT `fk_dokumen_lowongan_lowongan` FOREIGN KEY (`id_lowongan`) REFERENCES `lowongan_pekerjaan` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create dokumen_lamaran table (application documents)
CREATE TABLE `dokumen_lamaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lamaran` int(11) NOT NULL,
  `id_dokumen_lowongan` int(11) DEFAULT NULL,
  `jenis_dokumen` varchar(50) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `ukuran_file` int(11) NOT NULL,
  `tipe_file` varchar(50) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `diperbarui_pada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_lamaran` (`id_lamaran`),
  KEY `id_dokumen_lowongan` (`id_dokumen_lowongan`),
  CONSTRAINT `fk_dokumen_lamaran_lamaran` FOREIGN KEY (`id_lamaran`) REFERENCES `lamaran_pekerjaan` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_dokumen_lamaran_dokumen_lowongan` FOREIGN KEY (`id_dokumen_lowongan`) REFERENCES `dokumen_lowongan` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

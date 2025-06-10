-- Database: disiplin_hakim

CREATE DATABASE IF NOT EXISTS `disiplin_hakim` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `disiplin_hakim`;

-- --------------------------------------------------------

-- Tabel: users
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `foto_profil` varchar(255) DEFAULT 'default.jpg',
  `satker_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Tabel: satker (Satuan Kerja)
CREATE TABLE `satker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_satker` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Tabel: pegawai
CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `pangkat` varchar(50) DEFAULT NULL,
  `golongan` varchar(10) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `satker_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nip` (`nip`),
  KEY `satker_id` (`satker_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `pegawai_ibfk_1` FOREIGN KEY (`satker_id`) REFERENCES `satker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pegawai_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Tabel: kedisiplinan
CREATE TABLE `kedisiplinan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pegawai_id` int(11) NOT NULL,
  `bulan` int(2) NOT NULL,
  `tahun` int(4) NOT NULL,
  `terlambat` int(2) DEFAULT 0,
  `tidak_absen_masuk` int(2) DEFAULT 0,
  `pulang_awal` int(2) DEFAULT 0,
  `tidak_absen_pulang` int(2) DEFAULT 0,
  `keluar_tidak_izin` int(2) DEFAULT 0,
  `tidak_masuk_tanpa_ket` int(2) DEFAULT 0,
  `tidak_masuk_sakit` int(2) DEFAULT 0,
  `tidak_masuk_kerja` int(2) DEFAULT 0,
  `bentuk_pembinaan` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `pegawai_bulan_tahun` (`pegawai_id`,`bulan`,`tahun`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `kedisiplinan_ibfk_1` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kedisiplinan_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Tabel: tanda_tangan
CREATE TABLE `tanda_tangan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lokasi` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL,
  `nama_penandatangan` varchar(100) NOT NULL,
  `nip_penandatangan` varchar(30) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `tanda_tangan_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Tabel: laporan_file
CREATE TABLE `laporan_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_laporan` varchar(255) NOT NULL,
  `bulan` int(2) NOT NULL,
  `tahun` int(4) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('terkirim','dilihat','diterima','ditolak') NOT NULL DEFAULT 'terkirim',
  `feedback` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `laporan_file_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Tabel: notifikasi
CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `pesan` text NOT NULL,
  `jenis` enum('laporan','status','feedback','sistem') NOT NULL,
  `referensi_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Insert data default untuk admin dan user
INSERT INTO `satker` (`nama_satker`, `alamat`) VALUES
('Pengadilan Agama Bulukumba', 'Jl. Lanto Daeng Pasewang No. 18, Bulukumba, Sulawesi Selatan'),
('Pengadilan Agama Makassar', 'Jl. Perintis Kemerdekaan Km. 14, Makassar, Sulawesi Selatan'),
('Pengadilan Agama Maros', 'Jl. Jend. Sudirman No. 9, Maros, Sulawesi Selatan'),
('Pengadilan Tinggi Agama Makassar', 'Jl. AP Pettarani No. 58, Makassar, Sulawesi Selatan');

-- Insert data default untuk admin dan user dengan password admin123 dan user123
INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `email`, `role`, `satker_id`) VALUES
('admin', '$2y$10$qQPLCnOHBLGZnxnlmVBhAO/QIbMXnoGEg8cDEXCOUjOX9VFQbDOuO', 'Administrator', 'admin@example.com', 'admin', 4),
('user', '$2y$10$YlVyuGqrGUz.lCdxQxPxMuHGBCOtL9nzDfmbcKWOYrwkOKz3.8nMO', 'User Demo', 'user@example.com', 'user', 1);
-- Password untuk admin adalah 'admin123' dan untuk user adalah 'user123'

-- Insert data contoh untuk pegawai
INSERT INTO `pegawai` (`nama`, `nip`, `pangkat`, `golongan`, `jabatan`, `satker_id`, `created_by`) VALUES
('Laila Syahidan, S.Ag., M.H.', '197410172006042002', 'Pembina TK.I', 'IV/b', 'Ketua', 1, 2),
('Mudhirah, S.Ag., M.H.', '197104102005022001', 'Pembina', 'IV/a', 'Wakil Ketua', 1, 2),
('Indriyani Nasir, S.H.', '198906032017122001', 'Penata Muda TK.I', 'III/b', 'Hakim Pratama Muda', 1, 2),
('Fadhilyatun Mahmudah, S.H.I.', '199005252017122002', 'Penata Muda TK.I', 'III/b', 'Hakim Pratama Muda', 1, 2);

-- Insert data contoh untuk kedisiplinan
INSERT INTO `kedisiplinan` (`pegawai_id`, `bulan`, `tahun`, `terlambat`, `tidak_absen_masuk`, `pulang_awal`, `tidak_absen_pulang`, `keluar_tidak_izin`, `tidak_masuk_tanpa_ket`, `tidak_masuk_sakit`, `tidak_masuk_kerja`, `bentuk_pembinaan`, `keterangan`, `created_by`) VALUES
(1, 12, 2024, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 2),
(2, 12, 2024, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 2),
(3, 12, 2024, 3, 0, 1, 0, 0, 0, 0, 0, 'Sudah diberikan arahan oleh pimpinan', NULL, 2),
(4, 12, 2024, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 2);

-- Insert data contoh untuk tanda tangan
INSERT INTO `tanda_tangan` (`lokasi`, `tanggal`, `nama_jabatan`, `nama_penandatangan`, `nip_penandatangan`, `created_by`) VALUES
('Bulukumba', '2025-01-02', 'Ketua Pengadilan Agama Bulukumba', 'Laila Syahidan, S.Ag., M.H.', '197410172006042002', 2);

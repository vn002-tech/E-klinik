-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 1, 2026 at 01:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbklinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--
INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'super_admin'),
(2, 'pasien'),
(3, 'dokter'),
(4, 'apoteker'),
(5, 'resepsionis');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--
CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `login_session_key` varchar(255) DEFAULT NULL,
  `email_status` varchar(255) DEFAULT NULL,
  `password_expire_date` datetime DEFAULT '2027-02-05 00:00:00',
  `password_reset_key` varchar(255) DEFAULT NULL,
  `user_role_id` int(11) NOT NULL,
  PRIMARY KEY (`id_pengguna`),
  KEY `user_role_id` (`user_role_id`),
  CONSTRAINT `pengguna_ibfk_1` FOREIGN KEY (`user_role_id`) REFERENCES `roles` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengguna`
--
INSERT INTO `pengguna` (`id_pengguna`, `username`, `nama`, `jabatan`, `email`, `password`, `photo`, `login_session_key`, `email_status`, `password_expire_date`, `password_reset_key`, `user_role_id`) VALUES
(1, 'Admin', 'Paidi', 'Admin Data', 'vn002@gmail.com', '$2y$10$HnjpAiFtQ5CBWNhYXNz6tu/AMTsmSSuIe1uF7WNDwc.7D47hzRsTK', 'assets/images/favicon.png', NULL, NULL, '2027-02-05 00:00:00', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--
CREATE TABLE `role_permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `action_name` varchar(255) NOT NULL,
  PRIMARY KEY (`permission_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role_permissions`
--
INSERT INTO `role_permissions` (`role_id`, `page_name`, `action_name`) VALUES
(1, 'dokter', 'list'),
(1, 'dokter', 'view'),
(1, 'dokter', 'add'),
(1, 'dokter', 'edit'),
(1, 'dokter', 'editfield'),
(1, 'dokter', 'delete'),
(1, 'dokter', 'import_data'),
(1, 'obat', 'list'),
(1, 'obat', 'view'),
(1, 'obat', 'add'),
(1, 'obat', 'edit'),
(1, 'obat', 'editfield'),
(1, 'obat', 'delete'),
(1, 'obat', 'import_data'),
(1, 'pasien', 'list'),
(1, 'pasien', 'view'),
(1, 'pasien', 'add'),
(1, 'pasien', 'edit'),
(1, 'pasien', 'editfield'),
(1, 'pasien', 'delete'),
(1, 'pasien', 'import_data'),
(1, 'rekam_medis', 'list'),
(1, 'rekam_medis', 'view'),
(1, 'rekam_medis', 'add'),
(1, 'rekam_medis', 'edit'),
(1, 'rekam_medis', 'editfield'),
(1, 'rekam_medis', 'delete'),
(1, 'rekam_medis', 'import_data'),
(1, 'ruang', 'list'),
(1, 'ruang', 'view'),
(1, 'ruang', 'add'),
(1, 'ruang', 'edit'),
(1, 'ruang', 'editfield'),
(1, 'ruang', 'delete'),
(1, 'ruang', 'import_data'),
(1, 'pengguna', 'list'),
(1, 'pengguna', 'view'),
(1, 'pengguna', 'add'),
(1, 'pengguna', 'edit'),
(1, 'pengguna', 'editfield'),
(1, 'pengguna', 'delete'),
(1, 'pengguna', 'userregister'),
(1, 'pengguna', 'accountedit'),
(1, 'pengguna', 'accountview'),
(1, 'role_permissions', 'list'),
(1, 'role_permissions', 'view'),
(1, 'role_permissions', 'add'),
(1, 'role_permissions', 'edit'),
(1, 'role_permissions', 'editfield'),
(1, 'role_permissions', 'delete'),
(1, 'roles', 'list'),
(1, 'roles', 'view'),
(1, 'roles', 'add'),
(1, 'roles', 'edit'),
(1, 'roles', 'editfield'),
(1, 'roles', 'delete'),
(2, 'dokter', 'list'),
(2, 'dokter', 'view'),
(2, 'obat', 'list'),
(2, 'obat', 'view'),
(2, 'pasien', 'list'),
(2, 'pasien', 'view'),
(2, 'pasien', 'add'),
(2, 'rekam_medis', 'list'),
(2, 'rekam_medis', 'view'),
(2, 'rekam_medis', 'add'),
(2, 'rekam_medis', 'edit'),
(2, 'rekam_medis', 'editfield'),
(2, 'ruang', 'list'),
(2, 'ruang', 'view'),
(2, 'pengguna', 'accountedit'),
(2, 'pengguna', 'accountview');

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--
CREATE TABLE `dokter` (
  `id_dokter` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `nomor_hp` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  PRIMARY KEY (`id_dokter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dokter`
--
INSERT INTO `dokter` (`id_dokter`, `nama`, `nomor_hp`, `alamat`, `photo`) VALUES
(1, 'Dr. Andre Setijono', '081234567890', 'Poli Gigi', 'assets/images/favicon.png'),
(2, 'Dr. Hendra Irawan', '081234567891', 'Poli Umum', 'assets/images/favicon.png'),
(3, 'Dr. Awan Setiawan', '081234567892', 'Poli Anak', 'assets/images/favicon.png');

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--
CREATE TABLE `pasien` (
  `id_pasien` varchar(50) NOT NULL,
  `nama_pasien` varchar(255) NOT NULL,
  `jk` varchar(255) NOT NULL,
  `no_hp` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `keluhan` varchar(255) NOT NULL,
  PRIMARY KEY (`id_pasien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pasien`
--
INSERT INTO `pasien` (`id_pasien`, `nama_pasien`, `jk`, `no_hp`, `alamat`, `photo`, `keluhan`) VALUES
('000026001', 'Jordan', 'Laki-laki', '081234567893', 'Semarang', 'assets/images/favicon.png', 'Sakit Gigi'),
('000026002', 'Santi', 'Perempuan', '081234567894', 'Semarang', 'assets/images/favicon.png', 'Demam Tinggi'),
('000026003', 'Fina', 'Perempuan', '081234567895', 'Semarang', 'assets/images/favicon.png', 'Sesak Nafas');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--
CREATE TABLE `obat` (
  `id_obat` int(11) NOT NULL AUTO_INCREMENT,
  `nama_obat` varchar(255) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `stok` int(11) DEFAULT 100,
  `min_safety_threshold` int(11) DEFAULT 10,
  PRIMARY KEY (`id_obat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `obat`
--
INSERT INTO `obat` (`id_obat`, `nama_obat`, `keterangan`, `stok`, `min_safety_threshold`) VALUES
(1, 'Paracetamol', 'Obat pereda demam dan nyeri', 120, 10),
(2, 'Amoxicillin', 'Antibiotik infeksi bakteri', 50, 15),
(3, 'Bodrek', 'Obat sakit kepala', 5, 10);

-- --------------------------------------------------------

--
-- Table structure for table `ruang`
--
CREATE TABLE `ruang` (
  `id_ruang` int(11) NOT NULL AUTO_INCREMENT,
  `nama_ruang` varchar(255) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `max_capacity` int(11) DEFAULT 10,
  `current_occupancy` int(11) DEFAULT 0,
  PRIMARY KEY (`id_ruang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ruang`
--
INSERT INTO `ruang` (`id_ruang`, `nama_ruang`, `keterangan`, `max_capacity`, `current_occupancy`) VALUES
(1, 'Ruangan A', 'Poli Umum', 10, 2),
(2, 'Ruangan B', 'Poli Gigi', 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `rekam_medis`
--
CREATE TABLE `rekam_medis` (
  `id_medis` int(11) NOT NULL AUTO_INCREMENT,
  `id_pasien` varchar(50) DEFAULT NULL,
  `id_dokter` int(11) DEFAULT NULL,
  `id_obat` int(11) DEFAULT NULL,
  `id_ruang` int(11) DEFAULT NULL,
  `tanggal_periksa` date NOT NULL,
  `keluhan` text DEFAULT NULL,
  `diagnosa` text DEFAULT NULL,
  `status_antrean` enum('waiting','processing','completed') DEFAULT 'waiting',
  `triage_level` enum('routine','urgent','emergency') DEFAULT 'routine',
  PRIMARY KEY (`id_medis`),
  KEY `id_pasien` (`id_pasien`),
  KEY `id_dokter` (`id_dokter`),
  KEY `id_obat` (`id_obat`),
  KEY `id_ruang` (`id_ruang`),
  CONSTRAINT `rekam_medis_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`) ON DELETE SET NULL,
  CONSTRAINT `rekam_medis_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`) ON DELETE SET NULL,
  CONSTRAINT `rekam_medis_ibfk_3` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`) ON DELETE SET NULL,
  CONSTRAINT `rekam_medis_ibfk_4` FOREIGN KEY (`id_ruang`) REFERENCES `ruang` (`id_ruang`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rekam_medis`
--
INSERT INTO `rekam_medis` (`id_medis`, `id_pasien`, `id_dokter`, `id_obat`, `id_ruang`, `tanggal_periksa`, `keluhan`, `diagnosa`, `status_antrean`, `triage_level`) VALUES
(1, '000026001', 1, 1, 2, '2026-06-01', 'Sakit gigi geraham kiri bawah', 'Pulpitis reversible', 'waiting', 'routine'),
(2, '000026002', 2, 2, 1, '2026-06-01', 'Demam tinggi selama 3 hari', 'Dengue Fever', 'processing', 'urgent'),
(3, '000026003', 3, 3, 1, '2026-06-01', 'Nyeri dada mendadak dan sesak nafas', 'Cardiovascular Event', 'waiting', 'emergency');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--
CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `system_configs`
--
CREATE TABLE `system_configs` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(255) NOT NULL UNIQUE,
  `config_value` varchar(255) NOT NULL,
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_configs`
--
INSERT INTO `system_configs` (`config_key`, `config_value`) VALUES
('max_queue_per_doctor', '8');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

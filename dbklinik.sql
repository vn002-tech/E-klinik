-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2024 at 08:48 AM
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
-- Database: `emr`
--

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `id_dokter` int(11) NOT NULL,
  `nama_dokter` varchar(50) NOT NULL,
  `spesialis` varchar(50) NOT NULL,
  `nomor_hp` int(12) NOT NULL,
  `ruangan` varchar(50) NOT NULL,
  `jam_kerja` time NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokter`
--

INSERT INTO `dokter` (`id_dokter`, `nama_dokter`, `spesialis`, `nomor_hp`, `ruangan`, `jam_kerja`, `jenis_kelamin`) VALUES
(6, 'Drg. Neysa', 'Gigi dan Mulut', 89343432, 'Poli Gigi', '00:21:39', 'P');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id_obat` int(11) NOT NULL,
  `nama_obat` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `dosis` varchar(50) DEFAULT NULL,
  `intruksi` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id_obat`, `nama_obat`, `deskripsi`, `dosis`, `intruksi`) VALUES
(5, 'Paracetamol', 'lorm', '23', 'Lorem'),
(6, 'Antimo', 'lorem', '3', 'lorem');

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id_pasien` int(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `nomor_hp` int(11) NOT NULL,
  `gender` enum('L','P') NOT NULL,
  `keluhan` varchar(50) NOT NULL,
  `tanggal_kunjungan` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`id_pasien`, `nama`, `tanggal_lahir`, `alamat`, `nomor_hp`, `gender`, `keluhan`, `tanggal_kunjungan`) VALUES
(18, 'Jordan', '2024-11-14', 'Jawa tengah, Kota Semarang, Kec. Genuk, Genuksari,', 2147483647, 'L', 'sakit', '2024-11-14 21:26:07'),
(19, 'JCOB', '2024-11-14', 'Jawa tengah, Kota Semarang, Kec. Genuk, Genuksari,', 34341242, 'L', 'HALO', '4333-03-24 21:28:00'),
(21, 'jiso fruit', '2232-03-31', 'Jawa tengah, Kota Semarang, Kec. Genuk, Genuksari,', 9934324, 'P', 'ff', '2024-11-14 06:05:03');

-- --------------------------------------------------------

--
-- Table structure for table `rekam_medis`
--

CREATE TABLE `rekam_medis` (
  `id_rekam_medis` int(11) NOT NULL,
  `id_pasien` int(11) DEFAULT NULL,
  `id_dokter` int(11) DEFAULT NULL,
  `tanggal_kunjungan` date DEFAULT (curdate()),
  `diagnosis` text DEFAULT NULL,
  `pengobatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekam_medis`
--

INSERT INTO `rekam_medis` (`id_rekam_medis`, `id_pasien`, `id_dokter`, `tanggal_kunjungan`, `diagnosis`, `pengobatan`) VALUES
(28, 18, 6, '2024-11-14', 'halo', 'halo'),
(29, 21, 6, '2024-11-14', 'gak tau', 'iya kali');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id_dokter`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id_obat`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id_pasien`);

--
-- Indexes for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD PRIMARY KEY (`id_rekam_medis`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_dokter` (`id_dokter`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id_dokter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id_obat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id_pasien` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  MODIFY `id_rekam_medis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD CONSTRAINT `rekam_medis_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`),
  ADD CONSTRAINT `rekam_medis_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`);



-- Table structure for `pengguna`
CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `login_session_key` varchar(255) DEFAULT NULL,
  `email_status` varchar(255) DEFAULT NULL,
  `password_expire_date` datetime DEFAULT '2025-02-05 00:00:00',
  `password_reset_key` varchar(255) DEFAULT NULL,
  `user_role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table `pengguna`
INSERT INTO `pengguna` (`id_pengguna`, `username`, `nama`, `jabatan`, `email`, `password`, `photo`, `login_session_key`, `email_status`, `password_expire_date`, `password_reset_key`, `user_role_id`) VALUES
(1, 'Admin', 'Paidi', 'Admin Data', 'vn002@gmail.com', '$2y$10$HnjpAiFtQ5CBWNhYXNz6tu/AMTsmSSuIe1uF7WNDwc.7D47hzRsTK', 'http://localhost/e-klinik/uploads/files/lhpxvyj2k5148_r.png', NULL, NULL, '2025-02-05 00:00:00', NULL, 1),
(2, 'User', 'User', 'User Input Data', 'user@gmail.com', '$2y$10$qDYHLiuiCcf7/dYpx0/WjeK/KGeOOyn06Z5wo1TRAXluNOv3uq9yC', 'http://localhost/e-klinik/uploads/files/lsfi4r5wa3on2dk.jpg', NULL, NULL, '2025-02-05 00:00:00', NULL, 2);

-- Indexes/AUTO_INCREMENT for table `pengguna`
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`);
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- Table structure for `roles`
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table `roles`
INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Administrator'),
(2, 'User');

-- Indexes/AUTO_INCREMENT for table `roles`
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- Table structure for `role_permissions`
CREATE TABLE `role_permissions` (
  `permission_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `action_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table `role_permissions`
INSERT INTO `role_permissions` (`permission_id`, `role_id`, `page_name`, `action_name`) VALUES
(1, 1, 'dokter', 'list'),
(2, 1, 'dokter', 'view'),
(3, 1, 'dokter', 'add'),
(4, 1, 'dokter', 'edit'),
(5, 1, 'dokter', 'editfield'),
(6, 1, 'dokter', 'delete'),
(7, 1, 'dokter', 'import_data'),
(8, 1, 'obat', 'list'),
(9, 1, 'obat', 'view'),
(10, 1, 'obat', 'add'),
(11, 1, 'obat', 'edit'),
(12, 1, 'obat', 'editfield'),
(13, 1, 'obat', 'delete'),
(14, 1, 'obat', 'import_data'),
(15, 1, 'pasien', 'list'),
(16, 1, 'pasien', 'view'),
(17, 1, 'pasien', 'add'),
(18, 1, 'pasien', 'edit'),
(19, 1, 'pasien', 'editfield'),
(20, 1, 'pasien', 'delete'),
(21, 1, 'pasien', 'import_data'),
(22, 1, 'rekam_medis', 'list'),
(23, 1, 'rekam_medis', 'view'),
(24, 1, 'rekam_medis', 'add'),
(25, 1, 'rekam_medis', 'edit'),
(26, 1, 'rekam_medis', 'editfield'),
(27, 1, 'rekam_medis', 'delete'),
(28, 1, 'rekam_medis', 'import_data'),
(29, 1, 'ruang', 'list'),
(30, 1, 'ruang', 'view'),
(31, 1, 'ruang', 'add'),
(32, 1, 'ruang', 'edit'),
(33, 1, 'ruang', 'editfield'),
(34, 1, 'ruang', 'delete'),
(35, 1, 'ruang', 'import_data'),
(36, 1, 'pengguna', 'list'),
(37, 1, 'pengguna', 'view'),
(38, 1, 'pengguna', 'add'),
(39, 1, 'pengguna', 'edit'),
(40, 1, 'pengguna', 'editfield'),
(41, 1, 'pengguna', 'delete'),
(42, 1, 'pengguna', 'userregister'),
(43, 1, 'pengguna', 'accountedit'),
(44, 1, 'pengguna', 'accountview'),
(45, 1, 'role_permissions', 'list'),
(46, 1, 'role_permissions', 'view'),
(47, 1, 'role_permissions', 'add'),
(48, 1, 'role_permissions', 'edit'),
(49, 1, 'role_permissions', 'editfield'),
(50, 1, 'role_permissions', 'delete'),
(51, 1, 'roles', 'list'),
(52, 1, 'roles', 'view'),
(53, 1, 'roles', 'add'),
(54, 1, 'roles', 'edit'),
(55, 1, 'roles', 'editfield'),
(56, 1, 'roles', 'delete'),
(57, 2, 'dokter', 'list'),
(58, 2, 'dokter', 'view'),
(59, 2, 'obat', 'list'),
(60, 2, 'obat', 'view'),
(61, 2, 'pasien', 'list'),
(62, 2, 'pasien', 'view'),
(63, 2, 'pasien', 'add'),
(64, 2, 'rekam_medis', 'list'),
(65, 2, 'rekam_medis', 'view'),
(66, 2, 'rekam_medis', 'add'),
(67, 2, 'rekam_medis', 'edit'),
(68, 2, 'rekam_medis', 'editfield'),
(69, 2, 'ruang', 'list'),
(70, 2, 'ruang', 'view'),
(71, 2, 'pengguna', 'accountedit'),
(72, 2, 'pengguna', 'accountview');

-- Indexes/AUTO_INCREMENT for table `role_permissions`
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`permission_id`);
ALTER TABLE `role_permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

-- Table structure for `ruang`
CREATE TABLE `ruang` (
  `id_ruang` int(11) NOT NULL,
  `nama_ruang` varchar(255) NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table `ruang`
INSERT INTO `ruang` (`id_ruang`, `nama_ruang`, `keterangan`) VALUES
(1, 'Ruang A', '-'),
(2, 'Ruang B', '-');

-- Indexes/AUTO_INCREMENT for table `ruang`
ALTER TABLE `ruang`
  ADD PRIMARY KEY (`id_ruang`);
ALTER TABLE `ruang`
  MODIFY `id_ruang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 06, 2026 at 08:17 PM
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
-- Database: `fasilitas_kampus`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(30) NOT NULL,
  `nip` int(20) NOT NULL,
  `nama_admin` varchar(50) NOT NULL,
  `sandi` varchar(50) NOT NULL,
  `kontak` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nip`, `nama_admin`, `sandi`, `kontak`) VALUES
(5, 241011123, 'Arkan', 'ArkanKan23', 895172103),
(6, 241011124, 'Akmal', 'Akmalgalon23', 898821455),
(10, 0, '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_fasilitas`
--

CREATE TABLE `kategori_fasilitas` (
  `id_kategori` int(20) NOT NULL,
  `nama_kategori` enum('meja','kursi','ac','tv','dinding','lantai','atap','alat') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_fasilitas`
--

INSERT INTO `kategori_fasilitas` (`id_kategori`, `nama_kategori`) VALUES
(1, 'meja');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(20) NOT NULL,
  `Tingkat_Kerusakan` enum('Rendah','Sedang','Parah') NOT NULL,
  `Status_terkini` enum('Sedang Diperbaiki','Selesai') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `Tingkat_Kerusakan`, `Status_terkini`) VALUES
(1, 'Rendah', 'Sedang Diperbaiki');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_fasilitas`
--

CREATE TABLE `lokasi_fasilitas` (
  `id_lokasi` int(18) NOT NULL,
  `nama_ruangan` varchar(30) NOT NULL,
  `nama_gedung` enum('Gedung 1','Laboratorium','Gedung 2') NOT NULL,
  `Ruangan` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lokasi_fasilitas`
--

INSERT INTO `lokasi_fasilitas` (`id_lokasi`, `nama_ruangan`, `nama_gedung`, `Ruangan`) VALUES
(1, 'Ruang pemuda ', 'Gedung 2', '101');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(15) NOT NULL,
  `Nim` int(9) NOT NULL,
  `Nama_mahasiswa` varchar(50) NOT NULL,
  `Sandi` varchar(99) NOT NULL,
  `Kontak` int(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `Nim`, `Nama_mahasiswa`, `Sandi`, `Kontak`) VALUES
(1, 241011621, 'Asep', 'AsepKnalpot123', 888002188),
(3, 241414242, 'James', 'James200', 12123123);

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_status`
--

CREATE TABLE `riwayat_status` (
  `Id_riwayat` int(15) NOT NULL,
  `waktu` datetime NOT NULL,
  `status_terbaru` enum('Selesai','Belum Selesai') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_status`
--

INSERT INTO `riwayat_status` (`Id_riwayat`, `waktu`, `status_terbaru`) VALUES
(1, '2026-05-04 17:19:21', 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `teknisi`
--

CREATE TABLE `teknisi` (
  `id_teknisi` int(8) NOT NULL,
  `nama_teknisi` varchar(20) NOT NULL,
  `bidang_keahlian` varchar(20) NOT NULL,
  `kontak` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teknisi`
--

INSERT INTO `teknisi` (`id_teknisi`, `nama_teknisi`, `bidang_keahlian`, `kontak`) VALUES
(1, '', 'Arsitektur', 852801181),
(2, 'Asep ', 'Arsitektur', 888025011),
(3, 'Zukri', 'TeknikSipil', 898821425);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `FOREIGN_KEY` (`nip`);

--
-- Indexes for table `kategori_fasilitas`
--
ALTER TABLE `kategori_fasilitas`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`);

--
-- Indexes for table `lokasi_fasilitas`
--
ALTER TABLE `lokasi_fasilitas`
  ADD PRIMARY KEY (`id_lokasi`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `FOREIGN_KEY` (`Nim`);

--
-- Indexes for table `riwayat_status`
--
ALTER TABLE `riwayat_status`
  ADD PRIMARY KEY (`Id_riwayat`);

--
-- Indexes for table `teknisi`
--
ALTER TABLE `teknisi`
  ADD PRIMARY KEY (`id_teknisi`),
  ADD UNIQUE KEY `Kontak` (`kontak`),
  ADD UNIQUE KEY `id_teknisi_2` (`id_teknisi`),
  ADD UNIQUE KEY `id_teknisi_3` (`id_teknisi`),
  ADD KEY `id_teknisi` (`id_teknisi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kategori_fasilitas`
--
ALTER TABLE `kategori_fasilitas`
  MODIFY `id_kategori` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lokasi_fasilitas`
--
ALTER TABLE `lokasi_fasilitas`
  MODIFY `id_lokasi` int(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `riwayat_status`
--
ALTER TABLE `riwayat_status`
  MODIFY `Id_riwayat` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teknisi`
--
ALTER TABLE `teknisi`
  MODIFY `id_teknisi` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

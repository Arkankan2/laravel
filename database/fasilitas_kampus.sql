-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 11, 2026 at 04:55 AM
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
  `id_admin` int(10) UNSIGNED NOT NULL,
  `nip` bigint(20) NOT NULL,
  `nama_admin` varchar(50) NOT NULL,
  `sandi` varchar(255) NOT NULL,
  `kontak` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nip`, `nama_admin`, `sandi`, `kontak`, `created_at`, `updated_at`) VALUES
(3, 123456, 'Arkan', '$2y$12$Eej.53w.jvkOZjgR5hdot.C0r7sn/Yd9G66JGtqCFWVrwyOIuVfV2', 8123456789, '2026-06-10 17:30:23', '2026-06-10 17:30:23'),
(4, 241011126, 'Akuadmin', '$2y$12$/yQoyNjGNGvf8202btqai.H1lDG4/q/a5KCHlJM9EFE4pPjbZxxAO', 8821821, '2026-06-10 17:34:27', '2026-06-10 17:34:27');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_fasilitas`
--

CREATE TABLE `kategori_fasilitas` (
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `nama_kategori` enum('meja','kursi','ac','tv','dinding','lantai','atap','alat') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(10) UNSIGNED NOT NULL,
  `Tingkat_Kerusakan` enum('Rendah','Sedang','Parah') NOT NULL,
  `Status_terkini` enum('Sedang Diperbaiki','Selesai') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `Tingkat_Kerusakan`, `Status_terkini`, `created_at`, `updated_at`) VALUES
(1, 'Rendah', 'Sedang Diperbaiki', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_fasilitas`
--

CREATE TABLE `lokasi_fasilitas` (
  `id_lokasi` int(10) UNSIGNED NOT NULL,
  `nama_ruangan` varchar(30) NOT NULL,
  `nama_gedung` enum('Gedung 1','Laboratorium','Gedung 2') NOT NULL,
  `Ruangan` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(10) UNSIGNED NOT NULL,
  `Nim` bigint(20) NOT NULL,
  `Nama_mahasiswa` varchar(50) NOT NULL,
  `Sandi` varchar(255) NOT NULL,
  `Kontak` bigint(20) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `prodi` enum('Ilmu Komputer','Sistem Informasi','Matematika','Sains Data') DEFAULT NULL,
  `agama` enum('Islam','Kristen','Katolik','Hindu','Buddha') DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `ukm` varchar(100) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `Nim`, `Nama_mahasiswa`, `Sandi`, `Kontak`, `jenis_kelamin`, `prodi`, `agama`, `tanggal_lahir`, `ukm`, `foto_profil`, `created_at`, `updated_at`) VALUES
(1, 241011126, 'Fatih', '$2y$12$wfZ2wleRTCGHKoc.CDftFuGAv2NtZaSfPF16XNVE7mfOId3FXKRj.', 82821821, 'L', 'Ilmu Komputer', NULL, NULL, NULL, NULL, '2026-06-09 21:35:52', '2026-06-09 21:35:52'),
(3, 22222, 'raul', '$2y$10$fEc.uUI2Lob/o.Y6DmtZXuVfzuyPhb0eXyMhOiSvOj3l7nQ0zNfpy', 192, 'L', 'Ilmu Komputer', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 12345, 'akmal', '$2y$10$YzxL6I3fclD.zk4THayfoux0wYMKEUWlceCPIcyl/VMpksMfD8ne6', 1213, 'L', 'Ilmu Komputer', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 123321, 'imam', '$2y$10$r9OUSHcPn9nQcNEqdWXPWOOETTUa0cOK88okgwMhImsF47YUh3kJa', 11111, 'L', 'Sistem Informasi', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 241011129, 'Ismail', '$2y$12$1jFZIMEBemClD9OXl9yEX.mvYJ6laeyzUHgf1HjxO1CnHON2qF6dS', 828128, 'L', 'Ilmu Komputer', NULL, NULL, NULL, NULL, '2026-06-10 17:36:28', '2026-06-10 17:36:28'),
(7, 241011120, 'nopal', '$2y$12$SqLTQtX3R0ilcYnQsLgkBeRmWwIKzeFgtGToiAv9jOrA5r3wNctwO', 8828192, 'L', 'Ilmu Komputer', NULL, NULL, NULL, NULL, '2026-06-10 17:37:51', '2026-06-10 17:37:51'),
(8, 123456, 'indihome', '$2y$12$p.hMW.8m4ar9rWHn1hLgd.T1/zbVsYICFKQiBytJwEP9fpw9rHQ1q', 8829123, 'L', 'Ilmu Komputer', NULL, NULL, NULL, NULL, '2026-06-10 17:38:37', '2026-06-10 17:38:37'),
(9, 241011123, 'Arkan', '$2y$12$ipZpXDKlNO1Juo7DRSa9d.AZIgRYxcDM/BPmSfc52x6ygvepiV.va', 89982928222, 'L', 'Ilmu Komputer', NULL, NULL, NULL, NULL, '2026-06-10 17:39:34', '2026-06-10 17:39:34'),
(10, 24101112367, 'Arkan', '$2y$12$3hUNeFbuWytCZs/vvMoS1e5JP.lRKGJA/55yosy/xDxkL8Qf2d092', 89982928222, 'L', 'Ilmu Komputer', NULL, NULL, NULL, NULL, '2026-06-10 17:39:54', '2026-06-10 17:39:54'),
(12, 2410111266, 'mail', '$2y$12$WW9.DvNxmUbL5TNJda/hVumR4b1gLM34I6OyGnV1zDcpQrC8TajFa', 822182921, 'L', 'Ilmu Komputer', NULL, NULL, NULL, NULL, '2026-06-10 17:42:03', '2026-06-10 17:42:03'),
(13, 24101112602, 'mimi', '$2y$12$4XxakOE.wgM/LCzABQw7DeK4LcZdDvU0bteXZBaueX0Ut7CYHjygO', 8281823, 'L', 'Ilmu Komputer', NULL, NULL, NULL, NULL, '2026-06-10 17:42:58', '2026-06-10 17:42:58'),
(15, 12345678, 'aziz', '$2y$12$.YmFjzWyiyKo//daMxYRa.ch1/ZntAUDX4/1JonZa6J1KMjQinTCS', 89581222, 'L', 'Ilmu Komputer', NULL, NULL, NULL, NULL, '2026-06-10 17:49:32', '2026-06-10 17:49:32');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_10_000001_create_user_table', 1),
(5, '2026_06_10_000002_create_app_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_status`
--

CREATE TABLE `riwayat_status` (
  `Id_riwayat` int(10) UNSIGNED NOT NULL,
  `waktu` datetime NOT NULL,
  `status_terbaru` enum('Selesai','Belum Selesai') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `riwayat_status`
--

INSERT INTO `riwayat_status` (`Id_riwayat`, `waktu`, `status_terbaru`, `created_at`, `updated_at`) VALUES
(1, '2026-06-11 04:46:17', 'Belum Selesai', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teknisi`
--

CREATE TABLE `teknisi` (
  `id_teknisi` int(10) UNSIGNED NOT NULL,
  `nama_teknisi` varchar(20) NOT NULL,
  `bidang_keahlian` varchar(20) NOT NULL,
  `kontak` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','teknisi','dosen','mahasiswa') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(2, '241011126', '$2y$12$wfZ2wleRTCGHKoc.CDftFuGAv2NtZaSfPF16XNVE7mfOId3FXKRj.', 'mahasiswa', '2026-06-09 21:35:52', '2026-06-09 21:35:52'),
(3, '241011123', '$2y$10$.7e9ZMh0ZQLTht72z2xfsuwPUNRwazsIA7eIALXGWyXeo400Ble/q', 'mahasiswa', NULL, NULL),
(4, '22222', '$2y$10$q0qC1k/8JhR8w3o26yrINODrrrxj.StzQGTx6ZdCrVf8pMJyRFIoC', 'mahasiswa', NULL, NULL),
(5, '12345', '$2y$10$VEOpLL6fGFlqeSQHQF4UzuA4Gm5Gg.ZlkrcRQ6QCxOdiBLdqkd7ki', 'mahasiswa', NULL, NULL),
(6, '123321', '$2y$10$APOWHzOTS9ocl4UixEl98ukTWz9BY1AQpXJuh1f5oXOupfjl6xIEa', 'mahasiswa', NULL, NULL),
(8, 'arkan', '$2y$12$A8MMp6MRoT4mPcRY5P8mqe1VDWNOS2Ql2Lcy01xFhVMdDPeyma/J.', 'admin', '2026-06-10 17:30:23', '2026-06-10 17:34:00'),
(9, 'akuadmin', '$2y$12$/yQoyNjGNGvf8202btqai.H1lDG4/q/a5KCHlJM9EFE4pPjbZxxAO', 'admin', '2026-06-10 17:34:27', '2026-06-10 17:34:27'),
(10, '241011129', '$2y$12$1jFZIMEBemClD9OXl9yEX.mvYJ6laeyzUHgf1HjxO1CnHON2qF6dS', 'mahasiswa', '2026-06-10 17:36:29', '2026-06-10 17:36:29'),
(11, '241011120', '$2y$12$SqLTQtX3R0ilcYnQsLgkBeRmWwIKzeFgtGToiAv9jOrA5r3wNctwO', 'mahasiswa', '2026-06-10 17:37:51', '2026-06-10 17:37:51'),
(12, '123456', '$2y$12$p.hMW.8m4ar9rWHn1hLgd.T1/zbVsYICFKQiBytJwEP9fpw9rHQ1q', 'mahasiswa', '2026-06-10 17:38:37', '2026-06-10 17:38:37'),
(14, '24101112367', '$2y$12$3hUNeFbuWytCZs/vvMoS1e5JP.lRKGJA/55yosy/xDxkL8Qf2d092', 'mahasiswa', '2026-06-10 17:39:54', '2026-06-10 17:39:54'),
(16, '2410111266', '$2y$12$WW9.DvNxmUbL5TNJda/hVumR4b1gLM34I6OyGnV1zDcpQrC8TajFa', 'mahasiswa', '2026-06-10 17:42:03', '2026-06-10 17:42:03'),
(17, '24101112602', '$2y$12$4XxakOE.wgM/LCzABQw7DeK4LcZdDvU0bteXZBaueX0Ut7CYHjygO', 'mahasiswa', '2026-06-10 17:42:58', '2026-06-10 17:42:58'),
(19, '12345678', '$2y$12$.YmFjzWyiyKo//daMxYRa.ch1/ZntAUDX4/1JonZa6J1KMjQinTCS', 'mahasiswa', '2026-06-10 17:49:32', '2026-06-10 17:49:32'),
(20, 'superadmin', '1234', 'super_admin', '2026-06-06 18:28:21', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `admin_nip_unique` (`nip`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `mahasiswa_nim_unique` (`Nim`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `riwayat_status`
--
ALTER TABLE `riwayat_status`
  ADD PRIMARY KEY (`Id_riwayat`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `teknisi`
--
ALTER TABLE `teknisi`
  ADD PRIMARY KEY (`id_teknisi`),
  ADD UNIQUE KEY `teknisi_kontak_unique` (`kontak`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `user_username_unique` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_fasilitas`
--
ALTER TABLE `kategori_fasilitas`
  MODIFY `id_kategori` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lokasi_fasilitas`
--
ALTER TABLE `lokasi_fasilitas`
  MODIFY `id_lokasi` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `riwayat_status`
--
ALTER TABLE `riwayat_status`
  MODIFY `Id_riwayat` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teknisi`
--
ALTER TABLE `teknisi`
  MODIFY `id_teknisi` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

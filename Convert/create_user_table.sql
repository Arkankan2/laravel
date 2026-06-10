-- ============================================================
-- Script: Buat tabel `user` untuk database fasilitas_kampus
-- Deskripsi: Tabel user terpusat untuk sistem login OOP
-- Role: super_admin | admin | teknisi | dosen | mahasiswa
-- ============================================================

USE `fasilitas_kampus`;

-- Hapus tabel jika sudah ada (opsional, hati-hati di produksi)
-- DROP TABLE IF EXISTS `user`;

CREATE TABLE IF NOT EXISTS `user` (
    `id_user`   INT(11)      NOT NULL AUTO_INCREMENT,
    `username`  VARCHAR(50)  NOT NULL,
    `password`  VARCHAR(255) NOT NULL COMMENT 'Hashed dengan password_hash()',
    `role`      ENUM(
                    'super_admin',
                    'admin',
                    'teknisi',
                    'dosen',
                    'mahasiswa'
                ) NOT NULL DEFAULT 'mahasiswa',
    `created_at` DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP
                             ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_user`),
    UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci
  COMMENT='Tabel akun login terpusat';

-- ============================================================
-- Data awal (seed): satu akun untuk setiap role
-- Password default untuk semua akun: Password123!
-- GANTI password ini setelah pertama kali login!
-- ============================================================

INSERT INTO `user` (`username`, `password`, `role`) VALUES
(
    'superadmin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- Password123!
    'super_admin'
),
(
    'admin1',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
),
(
    'teknisi1',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'teknisi'
),
(
    'dosen1',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'dosen'
),
(
    'mahasiswa1',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'mahasiswa'
);

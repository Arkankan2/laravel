-- ============================================================
-- Script perbaikan: Update laporan dengan id_mahasiswa = NULL
-- yang seharusnya terhubung ke mahasiswa berdasarkan username
-- ============================================================
-- JALANKAN script ini SEKALI SAJA di phpMyAdmin atau MySQL console
-- setelah memastikan isi tabel `user` dan `mahasiswa` sudah benar.
-- ============================================================

-- Lihat laporan yang id_mahasiswa-nya NULL (belum terhubung)
SELECT 
    l.id_laporan,
    l.deskripsi,
    l.created_at,
    l.Status_terkini
FROM laporan l
WHERE l.id_mahasiswa IS NULL;

-- ============================================================
-- Jika laporan di atas memang dikirim oleh mahasiswa tertentu,
-- update manual satu per satu menggunakan query berikut:
-- (ganti <ID_LAPORAN> dan <NIM_MAHASISWA> sesuai data)
-- ============================================================

-- Contoh:
-- UPDATE laporan
-- SET id_mahasiswa = (SELECT id_mahasiswa FROM mahasiswa WHERE Nim = <NIM_MAHASISWA> LIMIT 1)
-- WHERE id_laporan = <ID_LAPORAN>;

-- ============================================================
-- ATAU: Hapus laporan NULL yang tidak valid (hati-hati!)
-- ============================================================
-- DELETE FROM laporan WHERE id_mahasiswa IS NULL;

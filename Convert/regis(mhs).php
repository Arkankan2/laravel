<?php
/**
 * regis(mhs).php — Registrasi Mahasiswa (OOP)
 * 
 * Menerima data dari regis(mhs).html dan menyimpannya ke tabel `mahasiswa`.
 * Password di-hash dengan password_hash() sebelum disimpan.
 * Menggunakan Prepared Statement untuk keamanan (anti SQL Injection).
 * Setelah berhasil, juga membuat akun di tabel `user` (role: mahasiswa).
 */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: regis(mhs).html');
    exit;
}

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/User.php';

// --- Ambil & bersihkan input ---
$nama_mhs      = trim($_POST['nama_mhs']       ?? '');
$nim           = trim($_POST['nim']            ?? '');
$prodi         = trim($_POST['prodi']          ?? '');
$kontak        = trim($_POST['kontak']         ?? '');
$sandi         = trim($_POST['sandi']          ?? '');
$jenis_kelamin = trim($_POST['jenis_kelamin']  ?? '');

// Validasi input tidak boleh kosong
if (empty($nama_mhs) || empty($nim) || empty($prodi) || empty($kontak) || empty($sandi) || empty($jenis_kelamin)) {
    echo "<script>
        alert('Semua field wajib diisi, termasuk jenis kelamin!');
        window.location = 'regis(mhs).html';
    </script>";
    exit;
}

// Validasi nilai jenis kelamin
if (!in_array($jenis_kelamin, ['L', 'P'], true)) {
    echo "<script>
        alert('Pilih jenis kelamin dengan benar!');
        window.location = 'regis(mhs).html';
    </script>";
    exit;
}

// --- Koneksi & OOP ---
$db        = Database::getInstance()->getConnection();
$userModel = new User($db);

// Hash password sebelum disimpan
$hashedPassword = password_hash($sandi, PASSWORD_BCRYPT);

try {
    // --- 1. Simpan ke tabel `mahasiswa` ---
    $stmtMhs = $db->prepare(
        "INSERT INTO mahasiswa (Nim, Nama_mahasiswa, prodi, Sandi, Kontak, jenis_kelamin)
         VALUES (:nim, :nama_mhs, :prodi, :sandi, :kontak, :jenis_kelamin)"
    );
    $stmtMhs->execute([
        ':nim'           => $nim,
        ':nama_mhs'      => $nama_mhs,
        ':prodi'         => $prodi,
        ':sandi'         => $hashedPassword,
        ':kontak'        => $kontak,
        ':jenis_kelamin' => $jenis_kelamin,
    ]);

    // --- 2. Buat akun login di tabel `user` (role: mahasiswa) ---
    // Username menggunakan NIM agar unik
    $usernameLogin = $nim;
    $userModel->create($usernameLogin, $sandi, 'mahasiswa');

    echo "<script>
        alert('Register mahasiswa berhasil!\\nUsername login Anda: $usernameLogin (NIM)');
        window.location = 'index.html';
    </script>";

} catch (PDOException $e) {
    // Cek duplikasi NIM
    if ($e->getCode() === '23000') {
        echo "<script>
            alert('NIM sudah terdaftar! Gunakan NIM yang benar atau login jika sudah punya akun.');
            window.location = 'regis(mhs).html';
        </script>";
    } else {
        echo "<script>
            alert('Register gagal: " . addslashes($e->getMessage()) . "');
            window.location = 'regis(mhs).html';
        </script>";
    }
}
?>
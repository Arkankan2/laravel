<?php
/**
 * regis.php — Registrasi Admin (OOP)
 * 
 * Menerima data dari regis.html dan menyimpannya ke tabel `admin`.
 * Password di-hash dengan password_hash() sebelum disimpan.
 * Menggunakan Prepared Statement untuk keamanan (anti SQL Injection).
 * Setelah berhasil, juga membuat akun di tabel `user` (role: admin).
 */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: regis.html');
    exit;
}

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/User.php';

// --- Ambil & bersihkan input ---
$nama_admin = trim($_POST['nama_admin'] ?? '');
$nip        = trim($_POST['nip']        ?? '');
$kontak     = trim($_POST['kontak']     ?? '');
$sandi      = trim($_POST['sandi']      ?? '');

// Validasi input tidak boleh kosong
if (empty($nama_admin) || empty($nip) || empty($kontak) || empty($sandi)) {
    echo "<script>
        alert('Semua field wajib diisi!');
        window.location = 'regis.html';
    </script>";
    exit;
}

// --- Koneksi & OOP ---
$db        = Database::getInstance()->getConnection();
$userModel = new User($db);

// Hash password sebelum disimpan
$hashedPassword = password_hash($sandi, PASSWORD_BCRYPT);

try {
    // --- 1. Simpan ke tabel `admin` ---
    $stmtAdmin = $db->prepare(
        "INSERT INTO admin (nip, nama_admin, sandi, kontak)
         VALUES (:nip, :nama_admin, :sandi, :kontak)"
    );
    $stmtAdmin->execute([
        ':nip'        => $nip,
        ':nama_admin' => $nama_admin,
        ':sandi'      => $hashedPassword,
        ':kontak'     => $kontak,
    ]);

    // --- 2. Buat akun login di tabel `user` (role: admin) ---
    // Username menggunakan nama_admin, pastikan unik
    $usernameLogin = strtolower(str_replace(' ', '_', $nama_admin));
    $userModel->create($usernameLogin, $sandi, 'admin');

    echo "<script>
        alert('Register admin berhasil! Username login: $usernameLogin');
        window.location = 'index.html';
    </script>";

} catch (PDOException $e) {
    // Cek duplikasi (error code 23000 = Duplicate entry)
    if ($e->getCode() === '23000') {
        echo "<script>
            alert('NIP atau username sudah terdaftar! Gunakan NIP yang berbeda.');
            window.location = 'regis.html';
        </script>";
    } else {
        echo "<script>
            alert('Register gagal: " . addslashes($e->getMessage()) . "');
            window.location = 'regis.html';
        </script>";
    }
}
?>
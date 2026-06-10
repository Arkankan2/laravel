<?php
/**
 * login.php
 * 
 * Entry point untuk proses login.
 * Hanya menerima request POST; semua logika ada di class Auth (OOP).
 * 
 * Alur:
 *  1. Load class-class OOP
 *  2. Buat instance Database (Singleton)
 *  3. Buat instance User & Auth
 *  4. Panggil $auth->login()
 *  5. Redirect sesuai role jika berhasil, atau kembali ke index.html jika gagal
 */

// Hanya izinkan method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// --- Load class OOP ---
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Auth.php';

// --- Ambil & bersihkan input ---
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validasi input dasar
if (empty($username) || empty($password)) {
    echo "<script>
        alert('Username dan password tidak boleh kosong!');
        window.location = 'index.html';
    </script>";
    exit;
}

// --- Bangun dependency chain (OOP / Dependency Injection) ---
// 1. Koneksi database (Singleton)
$db = Database::getInstance()->getConnection();

// 2. Model user
$userModel = new User($db);

// 3. Service autentikasi
$auth = new Auth($userModel);

// --- Proses login ---
if ($auth->login($username, $password)) {
    // Berhasil — ambil role lalu arahkan ke dashboard yang sesuai
    $role      = $auth->getCurrentRole();
    $dashboard = $auth->getDashboardByRole($role);

    header("Location: $dashboard");
    exit;
} else {
    // Gagal — kembali ke halaman login dengan pesan error
    echo "<script>
        alert('Username atau password salah!');
        window.location = 'index.html';
    </script>";
    exit;
}
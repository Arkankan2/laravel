<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/User.php';

/**
 * Class Auth
 * 
 * Mengelola proses autentikasi: login, logout, dan pengecekan sesi.
 * Menggunakan class User & Database (Dependency Injection + Composition).
 * 
 * Konsep OOP yang diterapkan:
 *  - Composition        : Auth "memiliki" User (bukan mewarisinya)
 *  - Dependency Injection : Database & User disuntikkan lewat constructor
 *  - Encapsulation      : logika auth tersembunyi di dalam class
 *  - Single Responsibility : class ini HANYA mengurus autentikasi
 */
class Auth
{
    private User $userModel;

    // Key yang dipakai untuk menyimpan data di $_SESSION
    private const SESSION_KEY = 'auth_user';

    /**
     * Constructor: terima objek User yang sudah siap pakai.
     *
     * @param User $userModel
     */
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    // =====================================================================
    //  METHOD UTAMA
    // =====================================================================

    /**
     * Proses login user.
     * 
     * Alur:
     *  1. Cari user berdasarkan username
     *  2. Verifikasi password dengan password_verify()
     *  3. Simpan data ke sesi jika valid
     *
     * @param string $username
     * @param string $plainPassword
     * @return bool  true jika login berhasil
     */
    public function login(string $username, string $plainPassword): bool
    {
        // Pastikan sesi sudah dimulai
        $this->startSession();

        // Cari user di database
        $userData = $this->userModel->findByUsername($username);

        if ($userData === null) {
            return false;  // Username tidak ditemukan
        }

        // Verifikasi password (password_verify aman terhadap timing attack)
        if (!password_verify($plainPassword, $userData['password'])) {
            return false;  // Password salah
        }

        // Login berhasil — simpan ke sesi
        $_SESSION[self::SESSION_KEY] = [
            'id_user'  => $userData['id_user'],
            'username' => $userData['username'],
            'role'     => $userData['role'],
        ];

        // Regenerasi session ID untuk mencegah Session Fixation Attack
        session_regenerate_id(true);

        return true;
    }

    /**
     * Logout: hancurkan semua data sesi.
     *
     * @return void
     */
    public function logout(): void
    {
        $this->startSession();

        // Hapus semua variabel sesi
        $_SESSION = [];

        // Hapus cookie sesi dari browser
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    /**
     * Mengecek apakah user sedang login.
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        $this->startSession();
        return isset($_SESSION[self::SESSION_KEY]);
    }

    /**
     * Mengambil data user yang sedang login dari sesi.
     *
     * @return array|null  Array ['id_user', 'username', 'role'] atau null
     */
    public function getCurrentUser(): ?array
    {
        $this->startSession();
        return $_SESSION[self::SESSION_KEY] ?? null;
    }

    /**
     * Mengambil role user yang sedang login.
     *
     * @return string|null
     */
    public function getCurrentRole(): ?string
    {
        $user = $this->getCurrentUser();
        return $user['role'] ?? null;
    }

    /**
     * Mengecek apakah user yang login memiliki salah satu dari role tertentu.
     * Berguna untuk membatasi akses halaman per role.
     *
     * Contoh penggunaan:
     *   $auth->hasRole(['super_admin', 'admin'])
     *
     * @param array $roles  Daftar role yang diizinkan
     * @return bool
     */
    public function hasRole(array $roles): bool
    {
        $currentRole = $this->getCurrentRole();
        if ($currentRole === null) {
            return false;
        }
        return in_array($currentRole, $roles, true);
    }

    /**
     * Guard: redirect ke halaman lain jika user belum login.
     * Panggil di awal setiap halaman yang butuh login.
     *
     * @param string $redirectTo  URL tujuan redirect (default: index.html)
     * @return void
     */
    public function requireLogin(string $redirectTo = '../index.html'): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: $redirectTo");
            exit;
        }
    }

    /**
     * Guard: redirect jika user tidak punya role yang dibutuhkan.
     *
     * @param array  $allowedRoles
     * @param string $redirectTo
     * @return void
     */
    public function requireRole(array $allowedRoles, string $redirectTo = '../index.html'): void
    {
        $this->requireLogin($redirectTo);

        if (!$this->hasRole($allowedRoles)) {
            header("Location: $redirectTo");
            exit;
        }
    }

    /**
     * Menentukan halaman dashboard berdasarkan role user.
     *
     * @param string $role
     * @return string  URL dashboard yang sesuai
     */
    public function getDashboardByRole(string $role): string
    {
        return match ($role) {
            'super_admin' => 'dashboard(adm).php',
            'admin'       => 'dashboard(adm).php',
            'teknisi'     => 'dashboard(adm).php',
            'dosen'       => 'dashboard(mhs).php',
            'mahasiswa'   => 'dashboard(mhs).php',
            default       => 'index.html',
        };
    }

    // =====================================================================
    //  HELPER PRIVATE
    // =====================================================================

    /**
     * Memulai sesi jika belum aktif.
     *
     * @return void
     */
    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

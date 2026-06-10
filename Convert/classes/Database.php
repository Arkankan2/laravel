<?php
/**
 * Class Database
 * 
 * Mengelola koneksi ke database menggunakan PDO (Singleton Pattern).
 * Dengan Singleton, hanya ada SATU instance koneksi sepanjang aplikasi berjalan
 * sehingga tidak membuat koneksi berulang-ulang yang boros resource.
 * 
 * Konsep OOP yang diterapkan:
 *  - Encapsulation  : properti koneksi disembunyikan (private)
 *  - Singleton      : satu instance, diakses lewat getInstance()
 */
class Database
{
    // --- Konfigurasi koneksi ---
    private string $host     = 'localhost';
    private string $dbName   = 'fasilitas_kampus';
    private string $dbUser   = 'root';
    private string $dbPass   = '';
    private string $charset  = 'utf8mb4';

    // Menyimpan instance PDO (koneksi aktif)
    private PDO $pdo;

    // Menyimpan satu-satunya objek Database (Singleton)
    private static ?Database $instance = null;

    /**
     * Constructor: private agar tidak bisa di-new dari luar.
     * Membuat koneksi PDO saat objek pertama kali dibuat.
     */
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Lempar exception saat error
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Hasil fetch berupa array asosiatif
            PDO::ATTR_EMULATE_PREPARES   => false,                    // Gunakan prepared statement asli
        ];

        try {
            $this->pdo = new PDO($dsn, $this->dbUser, $this->dbPass, $options);
        } catch (PDOException $e) {
            // Di produksi, jangan tampilkan pesan error ke user!
            die('Koneksi database gagal: ' . $e->getMessage());
        }
    }

    /**
     * Mencegah cloning objek (aturan Singleton).
     */
    private function __clone() {}

    /**
     * Mengembalikan satu-satunya instance Database.
     * Jika belum ada, buat baru; jika sudah ada, kembalikan yang ada.
     *
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Mengembalikan objek PDO untuk dipakai oleh class lain.
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}

<?php
require_once __DIR__ . '/Database.php';

/**
 * Class User
 * 
 * Merepresentasikan entitas "user" dalam sistem.
 * Bertanggung jawab atas operasi CRUD terkait tabel `user`.
 * 
 * Konsep OOP yang diterapkan:
 *  - Encapsulation  : data user disimpan sebagai properti private
 *  - Abstraction    : menyembunyikan detail query SQL di balik method
 *  - Single Responsibility : class ini HANYA mengurus data user (bukan autentikasi)
 */
class User
{
    // Properti privat — data user yang sedang aktif
    private int    $id;
    private string $username;
    private string $role;

    // Objek PDO untuk query database
    private PDO $db;

    // Daftar role yang valid dalam sistem
    public const VALID_ROLES = [
        'super_admin',
        'admin',
        'teknisi',
        'dosen',
        'mahasiswa',
    ];

    /**
     * Constructor: menerima koneksi PDO dari luar (Dependency Injection).
     *
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

   
    //  GETTER — akses properti dari luar secara terkontrol
   

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRole(): string
    {
        return $this->role;
    }

   
    //  METHOD UTAMA
   

    /**
     * Mencari user berdasarkan username.
     * Mengembalikan array data user atau null jika tidak ditemukan.
     *
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id_user, username, password, role
               FROM `user`
              WHERE username = :username
              LIMIT 1"
        );
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch();

        return $row ?: null;  // kembalikan null jika tidak ada hasil
    }

    /**
     * Mencari user berdasarkan id_user.
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id_user, username, role, created_at
               FROM `user`
              WHERE id_user = :id
              LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Membuat user baru di database.
     * Password di-hash otomatis menggunakan password_hash().
     *
     * @param string $username
     * @param string $plainPassword  Password BELUM di-hash
     * @param string $role
     * @return bool  true jika berhasil
     * @throws InvalidArgumentException jika role tidak valid
     */
    public function create(string $username, string $plainPassword, string $role): bool
    {
        if (!in_array($role, self::VALID_ROLES, true)) {
            throw new \InvalidArgumentException("Role '$role' tidak valid.");
        }

        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare(
            "INSERT INTO `user` (username, password, role)
             VALUES (:username, :password, :role)"
        );

        return $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':role'     => $role,
        ]);
    }

    /**
     * Memuat data user ke properti objek (mengisi state objek).
     *
     * @param array $data  Array hasil query dari findByUsername / findById
     * @return void
     */
    public function hydrate(array $data): void
    {
        $this->id       = (int) $data['id_user'];
        $this->username = $data['username'];
        $this->role     = $data['role'];
    }
}

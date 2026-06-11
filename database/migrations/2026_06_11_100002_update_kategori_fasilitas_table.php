<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah nama_kategori dari ENUM ke VARCHAR dan tambah deskripsi
        Schema::table('kategori_fasilitas', function (Blueprint $table) {
            if (!Schema::hasColumn('kategori_fasilitas', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('nama_kategori');
            }
        });

        // Ganti tipe kolom nama_kategori dari enum ke string
        // Menggunakan DB::statement karena alterColumn untuk enum perlu doctrine/dbal
        \DB::statement("ALTER TABLE kategori_fasilitas MODIFY COLUMN nama_kategori VARCHAR(100) NOT NULL");
    }

    public function down(): void
    {
        Schema::table('kategori_fasilitas', function (Blueprint $table) {
            if (Schema::hasColumn('kategori_fasilitas', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
        });
        \DB::statement("ALTER TABLE kategori_fasilitas MODIFY COLUMN nama_kategori ENUM('meja','kursi','ac','tv','dinding','lantai','atap','alat') NOT NULL");
    }
};

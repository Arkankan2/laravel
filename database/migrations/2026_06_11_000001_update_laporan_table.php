<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah data ke kategori_fasilitas jika kosong
        if (Schema::hasTable('kategori_fasilitas') && \DB::table('kategori_fasilitas')->count() === 0) {
            \DB::table('kategori_fasilitas')->insert([
                ['nama_kategori' => 'meja',    'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'kursi',   'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'ac',      'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'tv',      'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'dinding', 'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'lantai',  'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'atap',    'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'alat',    'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // Tambah data ke lokasi_fasilitas jika kosong
        if (Schema::hasTable('lokasi_fasilitas') && \DB::table('lokasi_fasilitas')->count() === 0) {
            \DB::table('lokasi_fasilitas')->insert([
                ['nama_ruangan' => 'Ruang Kelas 101', 'nama_gedung' => 'Gedung 1',      'Ruangan' => '101', 'created_at' => now(), 'updated_at' => now()],
                ['nama_ruangan' => 'Ruang Kelas 102', 'nama_gedung' => 'Gedung 1',      'Ruangan' => '102', 'created_at' => now(), 'updated_at' => now()],
                ['nama_ruangan' => 'Ruang Kelas 201', 'nama_gedung' => 'Gedung 1',      'Ruangan' => '201', 'created_at' => now(), 'updated_at' => now()],
                ['nama_ruangan' => 'Lab Komputer A',  'nama_gedung' => 'Laboratorium',  'Ruangan' => 'A',   'created_at' => now(), 'updated_at' => now()],
                ['nama_ruangan' => 'Lab Komputer B',  'nama_gedung' => 'Laboratorium',  'Ruangan' => 'B',   'created_at' => now(), 'updated_at' => now()],
                ['nama_ruangan' => 'Ruang Kelas 301', 'nama_gedung' => 'Gedung 2',      'Ruangan' => '301', 'created_at' => now(), 'updated_at' => now()],
                ['nama_ruangan' => 'Ruang Kelas 302', 'nama_gedung' => 'Gedung 2',      'Ruangan' => '302', 'created_at' => now(), 'updated_at' => now()],
                ['nama_ruangan' => 'Koridor Utama',   'nama_gedung' => 'Gedung 1',      'Ruangan' => '-',   'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // Tambah kolom ke tabel laporan jika belum ada
        Schema::table('laporan', function (Blueprint $table) {
            if (!Schema::hasColumn('laporan', 'id_mahasiswa')) {
                $table->unsignedInteger('id_mahasiswa')->nullable()->after('id_laporan');
            }
            if (!Schema::hasColumn('laporan', 'id_kategori')) {
                $table->unsignedInteger('id_kategori')->nullable()->after('id_mahasiswa');
            }
            if (!Schema::hasColumn('laporan', 'id_lokasi')) {
                $table->unsignedInteger('id_lokasi')->nullable()->after('id_kategori');
            }
            if (!Schema::hasColumn('laporan', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('id_lokasi');
            }
            if (!Schema::hasColumn('laporan', 'foto')) {
                $table->string('foto', 255)->nullable()->after('deskripsi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropColumn(['id_mahasiswa', 'id_kategori', 'id_lokasi', 'deskripsi', 'foto']);
        });
    }
};

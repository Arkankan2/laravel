<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('admin')) {
            Schema::create('admin', function (Blueprint $table) {
                $table->increments('id_admin');
                $table->bigInteger('nip')->unique();
                $table->string('nama_admin', 50);
                $table->string('sandi', 255);
                $table->bigInteger('kontak');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('mahasiswa')) {
            Schema::create('mahasiswa', function (Blueprint $table) {
                $table->increments('id_mahasiswa');
                $table->bigInteger('Nim')->unique();
                $table->string('Nama_mahasiswa', 50);
                $table->string('Sandi', 255);
                $table->bigInteger('Kontak');
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
                $table->enum('prodi', ['Ilmu Komputer', 'Sistem Informasi', 'Matematika', 'Sains Data'])->nullable();
                $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'])->nullable();
                $table->date('tanggal_lahir')->nullable();
                $table->string('ukm', 100)->nullable();
                $table->string('foto_profil', 255)->nullable();
                $table->timestamps();
            });
        } else {
            // Tambah kolom baru jika belum ada (untuk mahasiswa existing)
            Schema::table('mahasiswa', function (Blueprint $table) {
                if (!Schema::hasColumn('mahasiswa', 'jenis_kelamin')) {
                    $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('Kontak');
                }
                if (!Schema::hasColumn('mahasiswa', 'prodi')) {
                    $table->enum('prodi', ['Ilmu Komputer', 'Sistem Informasi', 'Matematika', 'Sains Data'])->nullable();
                }
                if (!Schema::hasColumn('mahasiswa', 'agama')) {
                    $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'])->nullable();
                }
                if (!Schema::hasColumn('mahasiswa', 'tanggal_lahir')) {
                    $table->date('tanggal_lahir')->nullable();
                }
                if (!Schema::hasColumn('mahasiswa', 'ukm')) {
                    $table->string('ukm', 100)->nullable();
                }
                if (!Schema::hasColumn('mahasiswa', 'foto_profil')) {
                    $table->string('foto_profil', 255)->nullable();
                }
            });
        }

        if (!Schema::hasTable('teknisi')) {
            Schema::create('teknisi', function (Blueprint $table) {
                $table->increments('id_teknisi');
                $table->string('nama_teknisi', 20);
                $table->string('bidang_keahlian', 20);
                $table->bigInteger('kontak')->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kategori_fasilitas')) {
            Schema::create('kategori_fasilitas', function (Blueprint $table) {
                $table->increments('id_kategori');
                $table->enum('nama_kategori', ['meja', 'kursi', 'ac', 'tv', 'dinding', 'lantai', 'atap', 'alat']);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lokasi_fasilitas')) {
            Schema::create('lokasi_fasilitas', function (Blueprint $table) {
                $table->increments('id_lokasi');
                $table->string('nama_ruangan', 30);
                $table->enum('nama_gedung', ['Gedung 1', 'Laboratorium', 'Gedung 2']);
                $table->string('Ruangan', 15);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('laporan')) {
            Schema::create('laporan', function (Blueprint $table) {
                $table->increments('id_laporan');
                $table->enum('Tingkat_Kerusakan', ['Rendah', 'Sedang', 'Parah']);
                $table->enum('Status_terkini', ['Sedang Diperbaiki', 'Selesai']);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('riwayat_status')) {
            Schema::create('riwayat_status', function (Blueprint $table) {
                $table->increments('Id_riwayat');
                $table->dateTime('waktu');
                $table->enum('status_terbaru', ['Selesai', 'Belum Selesai']);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_status');
        Schema::dropIfExists('laporan');
        Schema::dropIfExists('lokasi_fasilitas');
        Schema::dropIfExists('kategori_fasilitas');
        Schema::dropIfExists('teknisi');
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('admin');
    }
};

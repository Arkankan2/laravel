<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Gedung
        if (!Schema::hasTable('gedungs')) {
            Schema::create('gedungs', function (Blueprint $table) {
                $table->id();
                $table->string('nama_gedung', 100);
                $table->string('kode_gedung', 20)->nullable();
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });
        }

        // Tabel Lantai
        if (!Schema::hasTable('lantais')) {
            Schema::create('lantais', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_gedung');
                $table->string('nama_lantai', 100);
                $table->integer('nomor_lantai');
                $table->timestamps();

                $table->foreign('id_gedung')->references('id')->on('gedungs')->onDelete('cascade');
            });
        }

        // Tabel Ruangan
        if (!Schema::hasTable('ruangans')) {
            Schema::create('ruangans', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_lantai');
                $table->string('nama_ruangan', 100);
                $table->string('kode_ruangan', 30)->nullable();
                $table->text('deskripsi')->nullable();
                $table->timestamps();

                $table->foreign('id_lantai')->references('id')->on('lantais')->onDelete('cascade');
            });
        }

        // Tabel Audit Logs
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->nullable();
                $table->string('aktivitas', 100);
                $table->text('keterangan')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }

        // Tabel Backups
        if (!Schema::hasTable('backups')) {
            Schema::create('backups', function (Blueprint $table) {
                $table->id();
                $table->string('nama_file', 255);
                $table->unsignedBigInteger('ukuran_file')->default(0)->comment('bytes');
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangans');
        Schema::dropIfExists('lantais');
        Schema::dropIfExists('gedungs');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('backups');
    }
};

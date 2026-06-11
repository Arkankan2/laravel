<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'nama')) {
                $table->string('nama', 100)->nullable()->after('username');
            }
            if (!Schema::hasColumn('user', 'email')) {
                $table->string('email', 150)->nullable()->after('nama');
            }
            if (!Schema::hasColumn('user', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('role');
            }
            if (!Schema::hasColumn('user', 'foto_profil')) {
                $table->string('foto_profil', 255)->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn(['nama', 'email', 'status', 'foto_profil']);
        });
    }
};

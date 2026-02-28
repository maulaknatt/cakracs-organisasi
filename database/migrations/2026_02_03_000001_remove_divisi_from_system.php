<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus kolom divisi dari seluruh sistem.
     * Sistem hanya menggunakan: role (akses) dan jabatan (struktur).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('divisi');
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn('divisi');
        });

        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropColumn('divisi');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('divisi')->nullable()->after('email');
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->string('divisi')->nullable()->after('deskripsi');
        });

        Schema::table('anggotas', function (Blueprint $table) {
            $table->string('divisi')->nullable()->after('jabatan');
        });
    }
};

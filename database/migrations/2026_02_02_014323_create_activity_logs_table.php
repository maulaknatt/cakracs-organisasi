<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name')->nullable();
            $table->string('role')->nullable();
            $table->string('action'); // create, update, delete, login, logout, upload, etc
            $table->string('module'); // kegiatan, tugas, keuangan, dokumentasi, anggota, etc
            $table->unsignedBigInteger('target_id')->nullable(); // ID dari data yang diubah
            $table->text('description')->nullable(); // Deskripsi aksi
            $table->json('old_value')->nullable(); // Nilai sebelum perubahan
            $table->json('new_value')->nullable(); // Nilai setelah perubahan
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            // Index untuk performa query
            $table->index('user_id');
            $table->index('action');
            $table->index('module');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

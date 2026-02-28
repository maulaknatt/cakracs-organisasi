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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('divisi')->nullable();
            $table->unsignedBigInteger('penanggung_jawab')->nullable();
            $table->date('deadline')->nullable();
            $table->string('status')->default('belum');
            $table->unsignedBigInteger('kegiatan_id')->nullable();
            $table->timestamps();

            $table->foreign('penanggung_jawab')->references('id')->on('users')->onDelete('set null');
            $table->foreign('kegiatan_id')->references('id')->on('kegiatans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};

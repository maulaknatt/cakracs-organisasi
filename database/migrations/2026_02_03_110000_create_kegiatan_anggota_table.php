<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Anggota/panitia kegiatan manual (nama + jabatan), bukan link ke user.
     */
    public function up(): void
    {
        Schema::create('kegiatan_anggota', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kegiatan_id');
            $table->string('nama');
            $table->string('jabatan'); // Ketua, Wakil, Sekretaris, Bendahara, Anggota
            $table->timestamps();

            $table->foreign('kegiatan_id')->references('id')->on('kegiatans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_anggota');
    }
};

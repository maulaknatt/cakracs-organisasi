<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Panitia kegiatan: user yang terlibat dalam suatu kegiatan dengan jabatan.
     * Jabatan: Ketua, Wakil, Sekretaris, Bendahara, Anggota.
     */
    public function up(): void
    {
        Schema::create('kegiatan_panitia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kegiatan_id');
            $table->unsignedBigInteger('user_id');
            $table->string('jabatan'); // Ketua, Wakil, Sekretaris, Bendahara, Anggota
            $table->timestamps();

            $table->foreign('kegiatan_id')->references('id')->on('kegiatans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['kegiatan_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_panitia');
    }
};

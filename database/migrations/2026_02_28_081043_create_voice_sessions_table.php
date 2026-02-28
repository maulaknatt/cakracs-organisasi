<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voice_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('voice_channel_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('status')->default('connected'); // connected, disconnecting
            $table->timestamp('last_seen_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id']); // One session per user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_sessions');
    }
};

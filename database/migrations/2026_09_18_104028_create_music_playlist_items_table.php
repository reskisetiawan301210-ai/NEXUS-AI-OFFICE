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
        Schema::create('music_playlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('music_playlist_id')->constrained()->cascadeOnDelete();
            $table->string('youtube_id');
            $table->string('title');
            $table->string('channel_title')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('duration')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music_playlist_items');
    }
};

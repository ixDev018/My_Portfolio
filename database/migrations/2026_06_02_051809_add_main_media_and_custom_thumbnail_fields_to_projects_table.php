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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('main_media_type')->nullable(); // 'video' or 'image'
            $table->string('main_video_path')->nullable();
            $table->json('main_images')->nullable();
            $table->string('main_image_path')->nullable();
            $table->boolean('use_custom_thumbnail')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            //
        });
    }
};

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
        Schema::table('experiences', function (Blueprint $table) {
            $table->json('body_content')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('bg_media_type')->default('image'); // 'image', 'video', 'slideshow'
            $table->string('bg_media_path')->nullable();
            $table->json('bg_gallery_images')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn([
                'body_content',
                'is_active',
                'bg_media_type',
                'bg_media_path',
                'bg_gallery_images'
            ]);
        });
    }
};

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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('exp_default_bg_mode')->default('cycle'); // 'cycle' or 'custom'
            $table->string('exp_default_bg_type')->nullable(); // 'image', 'video', 'slideshow'
            $table->string('exp_default_bg_media_path')->nullable();
            $table->json('exp_default_bg_gallery_images')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'exp_default_bg_mode',
                'exp_default_bg_type',
                'exp_default_bg_media_path',
                'exp_default_bg_gallery_images'
            ]);
        });
    }
};

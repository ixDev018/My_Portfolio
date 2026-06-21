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
            $table->boolean('show_story')->default(true)->after('body_content');
            $table->json('coming_soon_gallery')->nullable()->after('show_story');
            $table->string('coming_soon_gallery_ratio')->default('16:9')->after('coming_soon_gallery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['show_story', 'coming_soon_gallery', 'coming_soon_gallery_ratio']);
        });
    }
};

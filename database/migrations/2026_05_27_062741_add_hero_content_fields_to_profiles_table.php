<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->integer('hero_blur_amount')->default(35)->after('hero_video_path');
            $table->longText('hero_html_content')->nullable()->after('hero_blur_amount');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['hero_blur_amount', 'hero_html_content']);
        });
    }
};

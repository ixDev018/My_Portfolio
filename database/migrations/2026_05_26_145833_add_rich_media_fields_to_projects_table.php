<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('thumbnail_type')->default('image')->after('thumbnail_path'); // 'image' or 'video'
            $table->string('thumbnail_video_path')->nullable()->after('thumbnail_type');
            $table->float('video_loop_start')->default(0)->after('thumbnail_video_path');
            $table->float('video_loop_end')->nullable()->after('video_loop_start');
            $table->string('date_published')->nullable()->after('year');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'thumbnail_type',
                'thumbnail_video_path',
                'video_loop_start',
                'video_loop_end',
                'date_published',
            ]);
        });
    }
};

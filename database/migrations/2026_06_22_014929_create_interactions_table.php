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
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index(); // 'page_view', 'project_view', 'cv_download', 'social_click'
            $table->unsignedBigInteger('project_id')->nullable()->index();
            $table->json('meta_data')->nullable(); // e.g. {"platform": "github"}
            $table->string('ip_address')->nullable(); // hashed or raw
            $table->string('user_agent')->nullable();
            $table->timestamps();

            // Setup foreign key constraint if you want, but often analytics are kept loose
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};

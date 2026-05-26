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
            $table->string('hero_top_text')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->dropColumn(['name', 'title', 'bio_short', 'bio_long']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['hero_top_text', 'hero_title', 'hero_subtitle']);
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->text('bio_short')->nullable();
            $table->text('bio_long')->nullable();
        });
    }
};

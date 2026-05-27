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
            $table->boolean('hero_gradient_enabled')->default(false);
            $table->string('hero_gradient_type')->default('linear'); // linear, radial
            $table->string('hero_gradient_start_color')->default('#000000');
            $table->string('hero_gradient_end_color')->default('#000000');
            $table->integer('hero_gradient_angle')->default(180);
            $table->integer('hero_gradient_opacity')->default(50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'hero_gradient_enabled',
                'hero_gradient_type',
                'hero_gradient_start_color',
                'hero_gradient_end_color',
                'hero_gradient_angle',
                'hero_gradient_opacity'
            ]);
        });
    }
};

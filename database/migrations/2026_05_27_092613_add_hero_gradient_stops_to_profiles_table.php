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
            $table->json('hero_gradient_stops')->nullable();
            
            // Drop old columns
            $table->dropColumn([
                'hero_gradient_start_color',
                'hero_gradient_end_color',
                'hero_gradient_opacity'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('hero_gradient_stops');
            
            // Re-add old columns
            $table->string('hero_gradient_start_color')->default('#000000');
            $table->string('hero_gradient_end_color')->default('#000000');
            $table->integer('hero_gradient_opacity')->default(50);
        });
    }
};

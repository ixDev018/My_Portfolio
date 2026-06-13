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
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropColumn('disable_modal');
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->boolean('disable_achievements_modal')->default(false)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('disable_achievements_modal');
        });
        Schema::table('achievements', function (Blueprint $table) {
            $table->boolean('disable_modal')->default(false)->after('media_path');
        });
    }
};

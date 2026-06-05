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
        Schema::table('tool_items', function (Blueprint $table) {
            $table->integer('proficiency')->default(5)->after('tooltip_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tool_items', function (Blueprint $table) {
            $table->dropColumn('proficiency');
        });
    }
};

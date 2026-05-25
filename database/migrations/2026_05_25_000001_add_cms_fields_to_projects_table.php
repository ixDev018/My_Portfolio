<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('title');
            $table->string('client')->nullable()->after('subtitle');
            $table->string('role')->nullable()->after('client');
            $table->string('year')->nullable()->after('role');
            $table->string('medium')->nullable()->after('year'); // e.g. "UI/UX", "Motion", "Graphic Art"
            $table->text('collaborators')->nullable()->after('medium'); // comma-separated names
            $table->longText('body_content')->nullable()->after('description'); // rich CMS story content
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'client', 'role', 'year', 'medium', 'collaborators', 'body_content']);
        });
    }
};

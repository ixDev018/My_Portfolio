<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('row_label'); // e.g. 'Programming Languages', 'Editing Tools', 'General Tools'
            $table->string('image_path')->nullable(); // cropped PNG stored in storage
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_items');
    }
};

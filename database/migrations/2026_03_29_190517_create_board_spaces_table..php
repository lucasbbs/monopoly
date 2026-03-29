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
        Schema::create('board_spaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('position')->unique();
            $table->enum('type', ['property', 'railroad', 'utility', 'tax', 'chance', 'community_chest', 'jail', 'free_parking', 'go_to_jail']);
            $table->string('color_group')->nullable();
            $table->integer('price')->nullable();
            $table->integer('base_rent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_spaces');
    }
};

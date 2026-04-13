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
        Schema::create('property_ownerships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained();
            $table->unsignedInteger('space_position');
            $table->foreignId('owner_game_player_id')->nullable()->constrained(table: 'game_players');
            $table->integer('houses')->default(0);
            $table->boolean('hotel')->default(false);
            $table->boolean('mortgaged')->default(false);
            $table->unique(['game_id', 'space_position']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_ownerships');
    }
};

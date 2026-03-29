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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['waiting', 'in_progress', 'finished'])->default('waiting');
            $table->foreignId('current_turn_player_id')->nullable()->constrained(table: 'game_players');
            $table->foreignId('winner_player_id')->nullable()->constrained(table: 'game_players');
            $table->timestamp('started_at', precision: 0)->nullable();
            $table->timestamp('ended_at', precision: 0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};

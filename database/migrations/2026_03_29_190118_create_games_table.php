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
            $table->enum('status', ['waiting', 'in_progress', 'finished']);
            $table->foreign('current_turn_player_id')->references('id')->on('users');
            $table->foreign('winner_player_id')->references('id')->on('users');
            $table->timestamp('started_at', precision: 0);
            $table->timestamp('ended_at', precision: 0);
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

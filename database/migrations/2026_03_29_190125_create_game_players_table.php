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
        Schema::create('game_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('turn_order')->default(0);
            $table->enum('token', ['boot', 'car', 'cat', 'dog', 'duck', 'horse', 'iron', 'penguin', 'ship', 'top_hat', 't_rex', 'wheel_barrow']);
            $table->integer('cash')->default(1500);
            $table->integer('position')->default(0);
            $table->boolean('in_jail')->default(false);
            $table->integer('jail_turns')->default(0);
            $table->boolean('is_bankrupt')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_players');
    }
};

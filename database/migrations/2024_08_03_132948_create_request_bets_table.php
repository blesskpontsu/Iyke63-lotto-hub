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
        Schema::create('request_bets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('company');
            $table->foreignId('user_id')->constrained('users');
            $table->string('game');
            $table->string('game_time')->nullable();
            $table->string('game_type');
            $table->string('game_code');
            $table->string('selected_numbers');
            $table->integer('amount');
            $table->integer('total_amount');
            $table->string('status')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_bets');
    }
};

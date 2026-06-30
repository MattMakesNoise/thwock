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
        Schema::create('final_hole_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained()->cascadeOnDelete();
            $table->foreignId('round_player_id')->constrained()->cascadeOnDelete();
            $table->integer('hole_number');
            $table->integer('strokes');
            $table->timestamps();

            $table->unique(['round_id', 'round_player_id', 'hole_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_hole_scores');
    }
};

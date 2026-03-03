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
        Schema::create('round_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained('rounds');
            $table->string('display_name');
            $table->string('email')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->integer('position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('round_players');
    }
};

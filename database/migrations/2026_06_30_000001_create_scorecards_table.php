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
        Schema::create('scorecards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->unique(['round_id', 'user_id']);
        });

        Schema::table('hole_scores', function (Blueprint $table) {
            $table->foreignId('scorecard_id')->nullable()->after('round_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hole_scores', function (Blueprint $table) {
            $table->dropConstrainedForeignId('scorecard_id');
        });

        Schema::dropIfExists('scorecards');
    }
};

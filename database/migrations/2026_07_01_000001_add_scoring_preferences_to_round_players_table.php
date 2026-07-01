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
        Schema::table('round_players', function (Blueprint $table) {
            $table->string('scoring_mode')->default('all_par_4')->after('position');
            $table->unsignedTinyInteger('handicap_strokes')->default(0)->after('scoring_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('round_players', function (Blueprint $table) {
            $table->dropColumn(['scoring_mode', 'handicap_strokes']);
        });
    }
};

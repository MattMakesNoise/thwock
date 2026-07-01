<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoundPlayer extends Model
{
    protected $fillable = [
        'round_id',
        'display_name',
        'email',
        'user_id',
        'position',
        'scoring_mode',
        'handicap_strokes',
    ];

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(HoleScore::class);
    }

    public function finalScores(): HasMany
    {
        return $this->hasMany(FinalHoleScore::class);
    }

    public function targetParForHole(Hole $hole): int
    {
        return match ($this->scoring_mode) {
            'actual' => $hole->par,
            'all_par_5' => 5,
            default => 4,
        };
    }
}

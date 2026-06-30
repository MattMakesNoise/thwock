<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoleScore extends Model
{
    protected $fillable = [
        'round_id',
        'scorecard_id',
        'round_player_id',
        'hole_number',
        'strokes',
    ];

    public function scorecard(): BelongsTo
    {
        return $this->belongsTo(Scorecard::class);
    }

    public function round(): BelongsTo {
        return $this->belongsTo( Round::class );
    }

    public function roundPlayer(): BelongsTo {
        return $this->belongsTo( RoundPlayer::class );
    }
}

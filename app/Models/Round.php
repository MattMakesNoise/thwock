<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Round extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
        'status',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function players(): HasMany
    {
        return $this->hasMany(RoundPlayer::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(HoleScore::class);
    }

    public function scorecards(): HasMany
    {
        return $this->hasMany(Scorecard::class);
    }

    public function finalScores(): HasMany
    {
        return $this->hasMany(FinalHoleScore::class);
    }
}

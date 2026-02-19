<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoundPlayer extends Model
{
    protected $fillable = [
        'round_id',
        'display_name',
        'position',
    ];

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }
}

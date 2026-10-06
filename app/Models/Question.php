<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // relasi belongs to ke model Subject
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // relasi has many ke model Answer
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}

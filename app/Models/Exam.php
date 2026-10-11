<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exam extends Model
{
    protected $guarded = [];

    protected $casts = [
        'conduct_at' => 'datetime',
        'expired_at' => 'datetime',
        'exact_time' => 'boolean',
        'min_score' => 'decimal:2',
    ];

    // relasi many to many dengan table subjects
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class)->withPivot('qty');
    }
}

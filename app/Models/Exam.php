<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exam extends Model
{
    protected $guarded = [];

    protected $cast = [
        'conducted_at' => 'datetime',
        'exact_time' => 'boolean',
        'expired_at' => 'datetime',
        'min_score' => 'decimal:2',
    ];

    // relasi many to many dengan table subjects
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class)->withPivot('qty');
    }
}

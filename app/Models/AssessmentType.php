<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentType extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'percentage',
        'status',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function studentResults(): HasMany
    {
        return $this->hasMany(StudentResult::class);
    }
}

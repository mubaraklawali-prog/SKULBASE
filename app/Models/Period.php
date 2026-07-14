<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'type',
        'start_time',
        'end_time',
        'duration_minutes',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'start_time' => 'date:H:i',
        'end_time' => 'date:H:i',
        'status' => 'boolean',
        'sort_order' => 'integer',
        'duration_minutes' => 'integer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }
}

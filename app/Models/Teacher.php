<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = [
        'school_id',
        'first_name',
        'last_name',
        'other_name',
        'gender',
        'email',
        'phone',
        'address',
        'qualification',
        'employment_date',
        'photo',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'employment_date' => 'date',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject')->withTimestamps();
    }

    public function schoolClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'teacher_school_class')->withTimestamps();
    }

    public function markedAttendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'marked_by');
    }

    public function getFullNameAttribute(): string
    {
        $name = $this->first_name.' '.$this->last_name;

        if ($this->other_name) {
            $name = $this->first_name.' '.$this->other_name.' '.$this->last_name;
        }

        return $name;
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && file_exists(storage_path('app/public/'.$this->photo))) {
            return asset('storage/'.$this->photo);
        }

        return asset('storage/defaults/avatar.png');
    }

    public function studentResults(): HasMany
    {
        return $this->hasMany(StudentResult::class);
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ParentModel extends Model
{
    protected $table = 'parents';

    protected $fillable = [
        'school_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Parent belongs to a school.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Parent belongs to a user account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Parent has many children.
     */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'parent_student',
            'parent_id',
            'student_id'
        )->withTimestamps();
    }

    /**
     * Full name accessor.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admission extends Model
{
    protected $fillable = [
        'school_id',
        'application_number',
        'full_name',
        'gender',
        'date_of_birth',
        'parent_name',
        'parent_phone',
        'parent_email',
        'address',
        'class_id',
        'previous_school',
        'passport',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function getPassportUrlAttribute(): ?string
    {
        return $this->passport ? asset('storage/'.$this->passport) : null;
    }

    public static function generateApplicationNumber(int $schoolId): string
    {
        $prefix = 'ADM-'.str_pad($schoolId, 3, '0', STR_PAD_LEFT);
        $date = now()->format('Ymd');
        $lastAdmission = self::where('application_number', 'like', "{$prefix}-{$date}-%")
            ->latest('id')
            ->value('application_number');

        if ($lastAdmission) {
            $sequence = (int) substr($lastAdmission, -4) + 1;
        } else {
            $sequence = 1;
        }

        return "{$prefix}-{$date}-".str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}

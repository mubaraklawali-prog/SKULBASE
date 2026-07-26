<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class School extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'school_type',
        'motto',
        'website',
        'email',
        'phone',
        'logo',
        'address',
        'city',
        'state',
        'country',
        'principal_name',
        'is_active',
        'registration_status',
        'registered_at',
        'approved_at',
        'rejected_at',
        'rejection_reason',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'registered_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function scopePending(Builder $query): Builder
    {
        return $query->where('registration_status', 'pending');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('registration_status', 'approved');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('registration_status', 'rejected');
    }

    public function isPending(): bool
    {
        return $this->registration_status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->registration_status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->registration_status === 'rejected';
    }

    public function hasRegistrationStatus(): bool
    {
        return $this->registration_status !== null;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function schoolClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function feeStructures(): HasMany
    {
        return $this->hasMany(FeeStructure::class);
    }

    public function feePayments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }

    public function assessmentTypes(): HasMany
    {
        return $this->hasMany(AssessmentType::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function gradingSystems(): HasMany
    {
        return $this->hasMany(GradingSystem::class);
    }

    public function studentResults(): HasMany
    {
        return $this->hasMany(StudentResult::class);
    }

    public function studentReportCards(): HasMany
    {
        return $this->hasMany(StudentReportCard::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }

    public function parents(): HasMany
    {
        return $this->hasMany(ParentModel::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }

    public function setting(): HasOne
    {
        return $this->hasOne(SchoolSetting::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->whereIn('status', ['trial', 'active', 'grace'])
            ->latest();
    }
}

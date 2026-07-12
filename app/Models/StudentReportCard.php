<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentReportCard extends Model
{
    protected $fillable = [
        'school_id',
        'exam_id',
        'student_id',
        'school_class_id',
        'total_score',
        'average_score',
        'overall_grade',
        'overall_remark',
        'class_position',
        'total_subjects',
        'subjects_passed',
        'subjects_failed',
        'attendance_percentage',
        'teacher_comment',
        'principal_comment',
        'status',
        'published_at',
    ];

    protected $casts = [
        'total_score' => 'decimal:2',
        'average_score' => 'decimal:2',
        'attendance_percentage' => 'decimal:2',
        'published_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function getIsDraftAttribute(): bool
    {
        return $this->status === 'draft';
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published';
    }
}

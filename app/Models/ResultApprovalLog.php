<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultApprovalLog extends Model
{
    protected $fillable = [
        'school_id',
        'student_report_card_id',
        'action',
        'old_status',
        'new_status',
        'performed_by',
        'remarks',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function studentReportCard(): BelongsTo
    {
        return $this->belongsTo(StudentReportCard::class);
    }

    public function performedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}

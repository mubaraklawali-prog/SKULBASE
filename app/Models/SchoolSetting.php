<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolSetting extends Model
{
    protected $fillable = [
        'school_id',
        'current_session',
        'current_term',
        'school_open_time',
        'school_close_time',
        'timezone',
        'date_format',
        'time_format',
        'currency',
        'currency_symbol',
        'maintenance_mode',
        'maintenance_message',
        'email_notifications',
        'assignment_notifications',
        'attendance_notifications',
        'result_notifications',
        'fee_notifications',
        'announcement_notifications',
        'event_notifications',
        'admission_notifications',
        'default_sender_name',
        'default_reply_email',
    ];

    protected $casts = [
        'school_open_time' => 'datetime:H:i',
        'school_close_time' => 'datetime:H:i',
        'maintenance_mode' => 'boolean',
        'email_notifications' => 'boolean',
        'assignment_notifications' => 'boolean',
        'attendance_notifications' => 'boolean',
        'result_notifications' => 'boolean',
        'fee_notifications' => 'boolean',
        'announcement_notifications' => 'boolean',
        'event_notifications' => 'boolean',
        'admission_notifications' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}

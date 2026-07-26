<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            $table->boolean('email_notifications')->default(true)->after('maintenance_message');
            $table->boolean('assignment_notifications')->default(true)->after('email_notifications');
            $table->boolean('attendance_notifications')->default(true)->after('assignment_notifications');
            $table->boolean('result_notifications')->default(true)->after('attendance_notifications');
            $table->boolean('fee_notifications')->default(true)->after('result_notifications');
            $table->boolean('announcement_notifications')->default(true)->after('fee_notifications');
            $table->boolean('event_notifications')->default(true)->after('announcement_notifications');
            $table->boolean('admission_notifications')->default(true)->after('event_notifications');
            $table->string('default_sender_name')->nullable()->after('admission_notifications');
            $table->string('default_reply_email')->nullable()->after('default_sender_name');
        });
    }

    public function down(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            $table->dropColumn([
                'email_notifications', 'assignment_notifications',
                'attendance_notifications', 'result_notifications',
                'fee_notifications', 'announcement_notifications',
                'event_notifications', 'admission_notifications',
                'default_sender_name', 'default_reply_email',
            ]);
        });
    }
};

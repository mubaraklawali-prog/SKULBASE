<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('school_id');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->index('status');
            $table->index(['school_id', 'attendance_date', 'status'], 'att_school_date_status_idx');
        });

        Schema::table('fee_payments', function (Blueprint $table) {
            $table->index(['school_id', 'payment_date', 'amount_paid'], 'fp_school_date_amount_idx');
        });

        Schema::table('student_report_cards', function (Blueprint $table) {
            $table->index(['school_id', 'exam_id', 'status'], 'src_school_exam_status_idx');
        });

        Schema::table('student_results', function (Blueprint $table) {
            $table->index(['school_id', 'school_class_id', 'exam_id'], 'sr_school_class_exam_idx');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['school_id']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('att_school_date_status_idx');
            $table->dropIndex(['status']);
        });

        Schema::table('fee_payments', function (Blueprint $table) {
            $table->dropIndex('fp_school_date_amount_idx');
        });

        Schema::table('student_report_cards', function (Blueprint $table) {
            $table->dropIndex('src_school_exam_status_idx');
        });

        Schema::table('student_results', function (Blueprint $table) {
            $table->dropIndex('sr_school_class_exam_idx');
        });
    }
};

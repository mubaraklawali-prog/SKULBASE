<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_approval_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_report_card_id')->constrained()->onDelete('cascade');
            $table->string('action');
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('school_id', 'ral_school_idx');
            $table->index('student_report_card_id', 'ral_card_idx');
            $table->index('action', 'ral_action_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_approval_logs');
    }
};

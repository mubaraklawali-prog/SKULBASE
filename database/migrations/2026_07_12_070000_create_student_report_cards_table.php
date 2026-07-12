<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_report_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
            $table->decimal('total_score', 8, 2)->default(0);
            $table->decimal('average_score', 5, 2)->default(0);
            $table->string('overall_grade')->nullable();
            $table->string('overall_remark')->nullable();
            $table->integer('class_position')->nullable();
            $table->integer('total_subjects')->default(0);
            $table->integer('subjects_passed')->default(0);
            $table->integer('subjects_failed')->default(0);
            $table->decimal('attendance_percentage', 5, 2)->nullable();
            $table->text('teacher_comment')->nullable();
            $table->text('principal_comment')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'exam_id'], 'src_student_exam_unique');
            $table->index('school_id', 'src_school_idx');
            $table->index('exam_id', 'src_exam_idx');
            $table->index('student_id', 'src_student_idx');
            $table->index('school_class_id', 'src_class_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_report_cards');
    }
};

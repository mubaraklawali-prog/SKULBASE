<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('assessment_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('score', 5, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'exam_id', 'subject_id', 'assessment_type_id'], 'sr_unique_score');
            $table->index('school_id', 'sr_school_idx');
            $table->index('exam_id', 'sr_exam_idx');
            $table->index('school_class_id', 'sr_class_idx');
            $table->index('student_id', 'sr_student_idx');
            $table->index('subject_id', 'sr_subject_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_results');
    }
};

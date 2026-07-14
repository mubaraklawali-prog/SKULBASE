<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('total_marks')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->date('due_date');
            $table->timestamps();

            $table->index('school_id', 'assignment_school_idx');
            $table->index('teacher_id', 'assignment_teacher_idx');
            $table->index('class_id', 'assignment_class_idx');
            $table->index('subject_id', 'assignment_subject_idx');
            $table->index('status', 'assignment_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};

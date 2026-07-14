<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_id')->constrained()->cascadeOnDelete();
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('school_id', 'timetable_school_idx');
            $table->index('class_id', 'timetable_class_idx');
            $table->index('teacher_id', 'timetable_teacher_idx');
            $table->index('period_id', 'timetable_period_idx');
            $table->index('day', 'timetable_day_idx');
            $table->index(['school_id', 'day'], 'timetable_school_day_idx');
            $table->index(['class_id', 'day', 'period_id'], 'timetable_class_day_period_idx');
            $table->index(['teacher_id', 'day', 'period_id'], 'timetable_teacher_day_period_idx');
            $table->unique(['class_id', 'section_id', 'day', 'period_id'], 'timetable_class_section_day_period_unique');
            $table->unique(['teacher_id', 'day', 'period_id'], 'timetable_teacher_day_period_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};

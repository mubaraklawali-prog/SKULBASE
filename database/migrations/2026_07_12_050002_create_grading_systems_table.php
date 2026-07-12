<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grading_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->decimal('min_score', 5, 2);
            $table->decimal('max_score', 5, 2);
            $table->string('grade');
            $table->string('remark');
            $table->decimal('grade_point', 4, 2)->nullable();
            $table->timestamps();

            $table->index(['school_id', 'min_score', 'max_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grading_systems');
    }
};

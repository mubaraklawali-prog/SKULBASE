<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['academic', 'break', 'lunch', 'assembly', 'other'])->default('academic');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_minutes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['school_id', 'name'], 'period_school_name_unique');
            $table->index(['school_id', 'sort_order'], 'period_school_sort_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
};

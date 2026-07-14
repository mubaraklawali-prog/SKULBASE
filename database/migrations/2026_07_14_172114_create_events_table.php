<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('event_type', ['academic', 'exam', 'holiday', 'meeting', 'sports', 'other'])->default('other');
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();

            $table->index('school_id', 'event_school_idx');
            $table->index('user_id', 'event_user_idx');
            $table->index('event_type', 'event_type_idx');
            $table->index('event_date', 'event_date_idx');
            $table->index('status', 'event_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

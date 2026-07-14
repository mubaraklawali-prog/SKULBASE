<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->enum('audience', ['everyone', 'teachers', 'students', 'parents'])->default('everyone');
            $table->string('attachment')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('school_id', 'announcement_school_idx');
            $table->index('user_id', 'announcement_user_idx');
            $table->index('audience', 'announcement_audience_idx');
            $table->index('status', 'announcement_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};

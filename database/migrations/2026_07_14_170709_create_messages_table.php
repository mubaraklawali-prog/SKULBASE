<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('recipient_role', ['teachers', 'students', 'parents'])->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('attachment')->nullable();
            $table->enum('status', ['unread', 'read'])->default('unread');
            $table->timestamps();

            $table->index('school_id', 'message_school_idx');
            $table->index('sender_id', 'message_sender_idx');
            $table->index('recipient_id', 'message_recipient_idx');
            $table->index('recipient_role', 'message_recipient_role_idx');
            $table->index('status', 'message_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

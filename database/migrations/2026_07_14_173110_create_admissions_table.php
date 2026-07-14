<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('application_number')->unique();
            $table->string('full_name');
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth');
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->string('parent_email')->nullable();
            $table->string('address');
            $table->foreignId('class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->string('previous_school')->nullable();
            $table->string('passport')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->index('school_id', 'admission_school_idx');
            $table->index('application_number', 'admission_app_number_idx');
            $table->index('status', 'admission_status_idx');
            $table->index('class_id', 'admission_class_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};

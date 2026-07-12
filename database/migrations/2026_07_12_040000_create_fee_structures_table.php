<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->string('term')->nullable();
            $table->string('session')->nullable();
            $table->date('due_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['school_id', 'school_class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};

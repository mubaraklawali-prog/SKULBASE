<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('monthly_price', 10, 2);
            $table->decimal('yearly_price', 10, 2);
            $table->integer('student_limit')->nullable();
            $table->boolean('is_unlimited')->default(false);
            $table->integer('trial_days')->default(30);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('is_active', 'plan_active_idx');
            $table->index('sort_order', 'plan_sort_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->string('referred_email')->nullable();
            $table->enum('status', ['registered', 'approved', 'converted', 'expired', 'cancelled'])->default('registered');
            $table->string('source')->nullable();
            $table->timestamp('first_paid_at')->nullable();
            $table->timestamp('commission_eligible_until')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();

            $table->unique(['affiliate_id', 'school_id'], 'referral_affiliate_school_unique');
            $table->index(['affiliate_id', 'school_id'], 'referral_affiliate_school_idx');
            $table->index('status', 'referral_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};

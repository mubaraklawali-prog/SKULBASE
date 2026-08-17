<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('referral_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->decimal('rate', 5, 2);
            $table->enum('type', ['first_payment', 'recurring', 'bonus'])->default('first_payment');
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending');
            $table->string('paid_period')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['referral_id', 'paid_period'], 'commission_referral_period_unique');
            $table->index('affiliate_id', 'commission_affiliate_idx');
            $table->index('referral_id', 'commission_referral_idx');
            $table->index('status', 'commission_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};

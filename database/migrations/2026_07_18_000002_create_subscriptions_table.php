<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->enum('status', ['trial', 'active', 'grace', 'expired', 'cancelled'])->default('trial');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('trial_starts_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('grace_ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->boolean('is_trial')->default(true);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('school_id', 'subscription_school_idx');
            $table->index('plan_id', 'subscription_plan_idx');
            $table->index('status', 'subscription_status_idx');
            $table->index('billing_cycle', 'subscription_billing_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

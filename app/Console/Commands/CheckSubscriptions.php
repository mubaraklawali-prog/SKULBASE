<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class CheckSubscriptions extends Command
{
    protected $signature = 'subscriptions:check';

    protected $description = 'Transition expired trials to grace period and expired grace periods to expired status';

    public function handle(SubscriptionService $subscriptionService): int
    {
        $transitions = $subscriptionService->checkAndTransitionSubscriptions();

        $this->info("Trial → Grace: {$transitions['trial_to_grace']} subscriptions");
        $this->info("Grace → Expired: {$transitions['grace_to_expired']} subscriptions");

        return Command::SUCCESS;
    }
}

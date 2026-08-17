<?php

namespace Database\Seeders;

use App\Models\AffiliateSetting;
use Illuminate\Database\Seeder;

class AffiliateSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'default_commission_rate' => '20.00',
            'commission_months' => '12',
            'min_payout_amount' => '10000.00',
        ];

        foreach ($settings as $key => $value) {
            AffiliateSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}

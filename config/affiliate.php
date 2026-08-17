<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Affiliate Program Defaults
    |--------------------------------------------------------------------------
    |
    | These values act as the fallback defaults for the affiliate program.
    | Super admins can override them at runtime via the affiliate_settings
    | table (seeded by AffiliateSettingSeeder).
    |
    */

    'default_commission_rate' => 20,

    'commission_months' => 12,

    'min_payout_amount' => 10000,

    'code_length' => 8,

];

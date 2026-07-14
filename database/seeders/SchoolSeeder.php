<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        School::updateOrCreate(
            ['slug' => 'skulbase-demo'],
            [
                'name' => 'Skulbase Demo School',
                'slug' => 'skulbase-demo',
                'email' => 'info@skulbase.com',
                'phone' => '+2348000000000',
                'address' => 'Sokoto, Nigeria',
                'city' => 'Sokoto',
                'state' => 'Sokoto',
                'country' => 'Nigeria',
                'is_active' => true,
            ]
        );
    }
}

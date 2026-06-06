<?php

namespace Database\Seeders;

use App\Models\TryoutPricing;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    public function run(): void
    {
        TryoutPricing::create([
            'tryout_quota_cost' => 1,
            'view_answer_quota_cost' => 1,
        ]);
    }
}

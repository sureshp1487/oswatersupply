<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Monthly Daily Supply',
                'slug' => 'monthly',
                'frequency' => 'daily',
                'deliveries_count' => 30,
                'discount_percentage' => 15.00,
                'badge' => '15% OFF • Most Popular',
                'description' => 'Water delivered fresh every single day for 30 days. Perfect for families, clinics, and offices.',
                'is_active' => true,
            ],
            [
                'name' => 'Alternate Days Saver',
                'slug' => 'alternate_days',
                'frequency' => 'alternate_days',
                'deliveries_count' => 15,
                'discount_percentage' => 10.00,
                'badge' => '10% OFF • Best Value',
                'description' => 'Refills delivered every 2 days (15 deliveries over a month). Ideal for medium households.',
                'is_active' => true,
            ],
            [
                'name' => 'Weekly Once Refill',
                'slug' => 'weekly_once',
                'frequency' => 'weekly',
                'deliveries_count' => 4,
                'discount_percentage' => 5.00,
                'badge' => '5% OFF • Light Use',
                'description' => 'Scheduled refills delivered once a week on your chosen day (4 deliveries per month).',
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}

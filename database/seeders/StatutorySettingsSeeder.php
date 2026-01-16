<?php

namespace Database\Seeders;

use App\Models\StatutorySetting;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StatutorySettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // NSSF Settings
            ['setting_name' => 'nssf_rate', 'setting_value' => 6.0, 'display_name' => 'NSSF Rate', 'category' => 'nssf', 'description' => 'NSSF contribution rate', 'effective_date' => '2024-01-01'],
            ['setting_name' => 'nssf_tier1_max', 'setting_value' => 420, 'display_name' => 'NSSF Tier I Max', 'category' => 'nssf', 'description' => 'Maximum Tier I contribution (KES 7,000 x 6%)', 'effective_date' => '2024-01-01'],
            ['setting_name' => 'nssf_tier2_threshold', 'setting_value' => 36000, 'display_name' => 'NSSF Tier II Threshold', 'category' => 'nssf', 'description' => 'Upper limit for Tier II', 'effective_date' => '2024-01-01'],
            ['setting_name' => 'nssf_tier2_max', 'setting_value' => 2160, 'display_name' => 'NSSF Max Contribution', 'category' => 'nssf', 'description' => 'Maximum total NSSF contribution', 'effective_date' => '2024-01-01'],

            // SHIF Settings
            ['setting_name' => 'shif_rate', 'setting_value' => 2.75, 'display_name' => 'SHIF Rate', 'category' => 'shif', 'description' => 'Social Health Insurance Fund rate', 'effective_date' => '2024-01-01'],

            // Housing Levy
            ['setting_name' => 'housing_levy_rate', 'setting_value' => 1.5, 'display_name' => 'Housing Levy Rate', 'category' => 'housing', 'description' => 'Affordable Housing Levy rate', 'effective_date' => '2024-01-01'],

            // PAYE Settings
            ['setting_name' => 'paye_band1_limit', 'setting_value' => 24000, 'display_name' => 'PAYE Band 1 Limit', 'category' => 'paye', 'description' => 'First KES 24,000', 'effective_date' => '2024-01-01'],
            ['setting_name' => 'paye_band1_rate', 'setting_value' => 10, 'display_name' => 'PAYE Band 1 Rate', 'category' => 'paye', 'description' => 'Tax rate for first band', 'effective_date' => '2024-01-01'],
            ['setting_name' => 'paye_band2_limit', 'setting_value' => 32333, 'display_name' => 'PAYE Band 2 Limit', 'category' => 'paye', 'description' => 'Up to KES 32,333', 'effective_date' => '2024-01-01'],
            ['setting_name' => 'paye_band2_rate', 'setting_value' => 25, 'display_name' => 'PAYE Band 2 Rate', 'category' => 'paye', 'description' => 'Tax rate for second band', 'effective_date' => '2024-01-01'],
            ['setting_name' => 'paye_band3_limit', 'setting_value' => 500000, 'display_name' => 'PAYE Band 3 Limit', 'category' => 'paye', 'description' => 'Up to KES 500,000', 'effective_date' => '2024-01-01'],
            ['setting_name' => 'paye_band3_rate', 'setting_value' => 30, 'display_name' => 'PAYE Band 3 Rate', 'category' => 'paye', 'description' => 'Tax rate for third band', 'effective_date' => '2024-01-01'],
            ['setting_name' => 'paye_band4_limit', 'setting_value' => 800000, 'display_name' => 'PAYE Band 4 Limit', 'category' => 'paye', 'description' => 'Up to KES 800,000', 'effective_date' => '2024-01-01'],
            ['setting_name' => 'paye_band4_rate', 'setting_value' => 32.5, 'display_name' => 'PAYE Band 4 Rate', 'category' => 'paye', 'description' => 'Tax rate for fourth band', 'effective_date' => '2024-01-01'],
            ['setting_name' => 'paye_band5_rate', 'setting_value' => 35, 'display_name' => 'PAYE Band 5 Rate', 'category' => 'paye', 'description' => 'Tax rate above KES 800,000', 'effective_date' => '2024-01-01'],
            
            // Reliefs
            ['setting_name' => 'personal_relief', 'setting_value' => 2400, 'display_name' => 'Personal Relief', 'category' => 'relief', 'description' => 'Monthly personal relief', 'effective_date' => '2024-01-01'],
            ['setting_name' => 'insurance_relief_max', 'setting_value' => 5000, 'display_name' => 'Insurance Relief Max', 'category' => 'relief', 'description' => 'Maximum monthly insurance relief', 'effective_date' => '2024-01-01'],
        ];

        foreach ($settings as $setting) {
            StatutorySetting::create($setting);
        }
    }
}

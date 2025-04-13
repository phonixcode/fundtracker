<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Fund;
use App\Models\FundReturn;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $growthFund = Fund::create([
            'name' => 'Growth Fund',
            'starting_balance' => 100000.00,
        ]);

        $equityFund = Fund::create([
            'name' => 'Equity Fund',
            'starting_balance' => 50000.00,
        ]);

        FundReturn::create([
            'fund_id' => $growthFund->id,
            'return_date' => Carbon::parse('2024-12-31'),
            'frequency' => 'yearly',
            'percentage' => 10.0,
            'is_compound' => true,
        ]);

        FundReturn::create([
            'fund_id' => $growthFund->id,
            'return_date' => Carbon::parse('2025-03-31'),
            'frequency' => 'quarterly',
            'percentage' => 2.5,
            'is_compound' => false,
        ]);

        FundReturn::create([
            'fund_id' => $equityFund->id,
            'return_date' => Carbon::parse('2025-03-31'),
            'frequency' => 'quarterly',
            'percentage' => 3.0,
            'is_compound' => true,
        ]);
    }
}

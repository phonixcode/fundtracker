<?php

namespace Database\Seeders;

use App\Models\Fund;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fund::create([
            'name' => 'Growth Fund',
            'starting_balance' => 100000.00,
        ]);

        Fund::create([
            'name' => 'Equity Fund',
            'starting_balance' => 50000.00,
        ]);
    }
}

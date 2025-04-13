<?php

namespace App\Console\Commands;

use App\Models\Fund;
use Illuminate\Console\Command;

class AddFund extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fund:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new investment fund';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Enter fund name');

        $balance = $this->ask('Enter starting balance (e.g. 100000)');
        if (!is_numeric($balance)) {
            $this->error('Invalid balance. Must be a number.');
            return;
        }

        $fund = Fund::create([
            'name' => $name,
            'starting_balance' => $balance,
        ]);

        $this->info("Fund '{$fund->name}' created with ID: {$fund->unique_id}");
    }
}

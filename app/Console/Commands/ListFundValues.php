<?php

namespace App\Console\Commands;

use App\Models\Fund;
use Illuminate\Console\Command;
use App\Services\FundReturnService;

class ListFundValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fund:list-values';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all funds with their current value';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $funds = Fund::all();

        if ($funds->isEmpty()) {
            $this->info('No funds found.');
            return;
        }

        $data = [];

        foreach ($funds as $fund) {
            try {
                $currentValue = app(FundReturnService::class)->calculateFundValueAt($fund, now());
                $data[] = [
                    'ID' => $fund->unique_id,
                    'Name' => $fund->name ?? 'N/A',
                    'Value (₦)' => number_format($currentValue, 2),
                ];
            } catch (\Exception $e) {
                $data[] = [
                    'ID' => $fund->unique_id,
                    'Name' => $fund->name ?? 'N/A',
                    'Value (₦)' => 'Error: ' . $e->getMessage(),
                ];
            }
        }

        $this->table(['Fund ID', 'Name', 'Current Value (₦)'], $data);
    }
}

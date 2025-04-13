<?php

namespace App\Console\Commands;

use App\Models\Fund;
use Illuminate\Console\Command;
use App\Services\FundReturnService;

class ListFundReturns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fund:returns-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all return entries for a given fund using its unique ID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fundId = $this->ask('Fund ID');

        $fund = Fund::where('unique_id', $fundId)->first();

        if (!$fund) {
            $this->error('Fund not found!');
            return;
        }

        $returns = app(FundReturnService::class)->getReturnHistory($fund);

        if ($returns->isEmpty()) {
            $this->info('No returns found for this fund.');
            return;
        }

        $this->table(
            ['ID', 'Date', 'Percentage', 'Frequency', 'Compound'],
            $returns->map(fn($r) => [
                $r->id,
                $r->return_date,
                "{$r->percentage}%",
                ucfirst($r->frequency->value),
                $r->is_compound ? 'Yes' : 'No'
            ])
        );
    }
}

<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Fund;
use Illuminate\Console\Command;
use App\Services\FundReturnService;

class GetFundValueAtDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fund:value-at-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and display the value of a fund at a specific date using its unique ID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fundId = $this->ask('Fund ID');
        $date = $this->ask('Date (YYYY-MM-DD)');

        $fund = Fund::where('unique_id', $fundId)->first();

        if (!$fund) {
            $this->error('Fund not found!');
            return;
        }

        try {
            $value = app(FundReturnService::class)->calculateFundValueAt($fund, Carbon::parse($date));
            $formattedValue = number_format($value, 2);
            $this->info("Fund value at {$date} is ₦{$formattedValue}");
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }

    }
}

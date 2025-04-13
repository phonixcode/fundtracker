<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Fund;
use App\Models\FundReturn;
use Illuminate\Console\Command;
use App\Services\FundReturnService;

class AddFundReturn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fund:return';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply a new return to a fund using its unique ID';

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

        $date = $this->ask('Return Date (YYYY-MM-DD)');
        try {
            $parsedDate = Carbon::createFromFormat('Y-m-d', $date);
        } catch (\Exception $e) {
            $this->error('Invalid date format. Please use YYYY-MM-DD.');
            return;
        }

        $frequency = $this->choice('Frequency', ['monthly', 'quarterly', 'yearly']);

        $percentage = $this->ask('Return percentage (e.g., 5)');
        if (!is_numeric($percentage)) {
            $this->error('Percentage must be a numeric value.');
            return;
        }

        if ($percentage < -100 || $percentage > 100) {
            $this->error('Percentage must be between -100 and 100.');
            return;
        }

        $isCompound = $this->confirm('Is it compound?', true);

        $fundReturn = new FundReturn([
            'return_date' => $parsedDate->format('Y-m-d'),
            'frequency' => $frequency,
            'percentage' => $percentage,
            'is_compound' => filter_var($isCompound, FILTER_VALIDATE_BOOLEAN),
        ]);

        app(FundReturnService::class)->applyReturn($fund, $fundReturn);

        $this->info('Return applied and fund updated successfully.');
    }

}

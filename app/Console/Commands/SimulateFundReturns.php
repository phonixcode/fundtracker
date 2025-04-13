<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Fund;
use App\Models\FundReturn;
use Illuminate\Console\Command;
use App\Services\FundReturnService;

class SimulateFundReturns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fund:simulate-return
                            {--preview : Show what would happen without saving}
                            {--date= : Manually specify the return date (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate a return (monthly, quarterly, yearly) for all funds with optional preview and custom date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $frequency = $this->choice('Select return frequency', ['monthly', 'quarterly', 'yearly'], 0);
        $percentage = $this->ask('Enter return percentage (e.g., 2)');

        if (!is_numeric($percentage)) {
            $this->error('Percentage must be numeric.');
            return;
        }

        $isCompound = $this->confirm('Is the return compound?', true);

        // Handle date input or auto-generate
        $returnDateInput = $this->option('date');
        $returnDate = $returnDateInput
            ? Carbon::createFromFormat('Y-m-d', $returnDateInput)
            : $this->getReturnDate($frequency);

        $preview = $this->option('preview');

        $this->info(($preview ? 'PREVIEW MODE: ' : '') . "Simulating {$percentage}% {$frequency} return for " . $returnDate->toDateString());

        $funds = Fund::all();

        if ($funds->isEmpty()) {
            $this->info('No funds found to simulate returns.');
            return;
        }

        foreach ($funds as $fund) {
            $summary = "Fund ID: {$fund->unique_id} | Date: {$returnDate->toDateString()} | {$percentage}% | Compound: " . ($isCompound ? 'Yes' : 'No');

            if ($preview) {
                $this->line("[PREVIEW] {$summary}");
            } else {
                $fundReturn = new FundReturn([
                    'return_date' => $returnDate->format('Y-m-d'),
                    'frequency' => $frequency,
                    'percentage' => $percentage,
                    'is_compound' => $isCompound,
                ]);

                try {
                    app(FundReturnService::class)->applyReturn($fund, $fundReturn);
                    $this->info("{$summary}");
                } catch (\Exception $e) {
                    $this->error("Error for Fund ID {$fund->unique_id}: " . $e->getMessage());
                }
            }
        }

        if ($preview) {
            $this->info("Preview complete. No changes were applied.");
        } else {
            $this->info("Return simulation and application complete.");
        }
    }

    private function getReturnDate(string $frequency)
    {
        $now = Carbon::now();

        return match ($frequency) {
            'monthly' => $now->copy()->endOfMonth(),
            'quarterly' => $now->copy()->addMonths(3 - ($now->month - 1) % 3)->startOfMonth()->subDay(),
            'yearly' => $now->copy()->endOfYear(),
            default => $now,
        };
    }
}

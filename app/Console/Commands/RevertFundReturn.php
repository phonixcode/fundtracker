<?php

namespace App\Console\Commands;

use App\Models\FundReturn;
use Illuminate\Console\Command;
use App\Services\FundReturnService;

class RevertFundReturn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fund:return:revert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revert a specific fund return by its ID and recalculate the fund value';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $returnId = $this->ask('FundReturn ID to revert');

        $fundReturn = FundReturn::findOrFail($returnId);

        app(FundReturnService::class)->revertReturn($fundReturn);

        $this->info('Return reverted and fund recalculated.');
    }

}

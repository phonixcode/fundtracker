<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Fund;
use App\Models\FundReturn;
use Illuminate\Support\Collection;

class FundReturnService
{
    public function applyReturn(Fund $fund, FundReturn $newReturn)
    {
        if ($fund->returns()->where('return_date', $newReturn->return_date)->exists()) {
            throw new \Exception('A return already exists for this date.');
        }

        $newReturn->fund_id = $fund->id;
        $newReturn->save();

        $this->recalculateFundValue($fund);
    }

    public function revertReturn(FundReturn $return)
    {
        $return->is_reverted = true;
        $return->save();

        $this->recalculateFundValue($return->fund);
    }

    public function recalculateFundValue(Fund $fund)
    {
        $startingBalance = $fund->starting_balance;
        $currentValue = $startingBalance;

        $returns = $fund->returns()
            ->where('is_reverted', false)
            ->orderBy('return_date')
            ->get();

        foreach ($returns as $return) {
            if ($return->is_compound) {
                $currentValue += ($currentValue * ($return->percentage / 100));
            } else {
                $currentValue += ($startingBalance * ($return->percentage / 100));
            }
        }

        $fund->current_value = round($currentValue, 2);
        $fund->save();
    }

    public function calculateFundValueAt(Fund $fund, Carbon $date)
    {
        $startingBalance = $fund->starting_balance;

        $returns = $fund->returns()
            ->where('return_date', '<=', $date->toDateString())
            ->where('is_reverted', false)
            ->orderBy('return_date')
            ->get();

        $currentValue = $startingBalance;

        foreach ($returns as $return) {
            $percentage = $return->percentage;

            if ($return->is_compound) {
                $currentValue += ($currentValue * ($percentage / 100));
            } else {
                $currentValue += ($startingBalance * ($percentage / 100));
            }
        }

        return round($currentValue, 2);
    }

    public function getReturnHistory(Fund $fund)
    {
        return $fund->returns()
            ->where('is_reverted', false)
            ->orderBy('return_date')
            ->get();
    }
}

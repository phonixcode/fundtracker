<?php

namespace App\Models;

use App\Enums\ReturnFrequency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'fund_id',
        'return_date',
        'frequency',
        'percentage',
        'is_compound',
        'is_reverted',
    ];

    protected $casts = [
        'frequency'     => ReturnFrequency::class,
        'is_compound'   => 'boolean',
        'is_reverted'   => 'boolean',
    ];

    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }
}

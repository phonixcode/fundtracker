<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Fund extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'starting_balance', 'current_value'];

    protected $casts = [
        'starting_balance' => 'float',
        'current_value'    => 'float',
    ];


    protected static function booted()
    {
        static::creating(function ($fund) {
            $fund->unique_id = self::generateUniqueId();
            $fund->current_value = $fund->starting_balance;
        });
    }

    public function returns()
    {
        return $this->hasMany(FundReturn::class);
    }

    public static function generateUniqueId()
    {
        do {
            $unique = 'F-' . strtoupper(Str::random(4));
        } while (self::where('unique_id', $unique)->exists());

        return $unique;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'starting_balance'];

    public function returns()
    {
        return $this->hasMany(FundReturn::class);
    }
}

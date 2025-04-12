<?php

namespace App\Enums;

enum ReturnFrequency: string
{
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case YEARLY = 'yearly';
}

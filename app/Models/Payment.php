<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Payment extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'reference_number',
        'checkout_url',
        'amount',
        'method',
        'remarks',
        'status',
    ];
}

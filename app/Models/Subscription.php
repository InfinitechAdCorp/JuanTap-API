<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Subscription extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'plan',
        'bs',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

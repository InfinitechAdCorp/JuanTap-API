<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan',
        'bs',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

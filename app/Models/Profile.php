<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Profile extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'template_id',
        'name',
        'location',
        'bio',
        'avatar',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

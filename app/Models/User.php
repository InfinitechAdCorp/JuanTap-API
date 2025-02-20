<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUlids;

    protected $fillable = [
        'email',
        'password',
        'type',
        'provider',
        'provider_account_id',
        'reset_token',
    ];

    public static function booted()
    {
        self::creating(function (User $record) {
            $record->reset_token = Str::random();
        });
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
}

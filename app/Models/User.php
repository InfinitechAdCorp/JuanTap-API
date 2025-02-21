<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, HasUlids, HasApiTokens, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'type',
    ];

    public function provider(): HasOne
    {
        return $this->hasOne(Provider::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'type',
        'reset_token',
    ];

    public static function booted()
    {
        self::creating(function (User $record) {
            $record->id = Str::ulid();
            $record->reset_token = Str::random();
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $keyType = 'string';
    public $incrementing = false;

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

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
}

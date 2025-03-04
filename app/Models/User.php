<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, HasUlids;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    public function provider()
    {
        return $this->hasOne(Provider::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function templates()
    {
        return $this->belongsToMany(Template::class)->withPivot('published');
    }

    public function published_template()
    {
        return $this->belongsToMany(Template::class)->withPivot('published')->wherePivot('published', 1);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, HasUlids, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [];

    protected $attributes = [
        'role' => 'User'
    ];

    public function provider()
    {
        return $this->hasOne(Provider::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function favorites_templates()
    {
        return $this->belongsToMany(Template::class, 'favorites');
    }

    public function customizations_templates()
    {
        return $this->belongsToMany(Template::class, 'customizations');
    }

    public function collections_templates()
    {
        return $this->belongsToMany(Template::class, 'collections')->withPivot('is_published');
    }

    public function payments_templates()
    {
        return $this->belongsToMany(Template::class, 'payments')->withPivot(['remarks', 'method', 'status', 'proof']);
    }
}

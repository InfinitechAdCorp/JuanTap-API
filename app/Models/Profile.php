<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'name',
        'location',
        'bio',
        'avatar',
    ];

    public static function booted()
    {
        self::updated(function (Profile $record): void {
            $avatar = $record->getOriginal('avatar');
            Storage::disk('s3')->delete("avatars/$avatar");
        });

        self::deleted(function (Profile $record): void {
            Storage::disk('s3')->delete("avatars/$record->avatar");
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function socials()
    {
        return $this->hasMany(Social::class);
    }
}

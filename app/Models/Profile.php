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

    protected $attributes = [
        'name' => '',
        'location' => '',
        'bio' => '',
        'avatar' => '',
    ];

    public static function booted()
    {
        self::updated(function (Profile $record): void {
            $directory = "avatars";
            $key  = "avatar";
            if ($record->wasChanged($key)) {
                Storage::disk('s3')->delete("$directory/" . $record->getOriginal($key));
            }
        });

        self::deleted(function (Profile $record): void {
            $directory = "avatars";
            $key  = "avatar";
            Storage::disk('s3')->delete("$directory/" . $record[$key]);
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

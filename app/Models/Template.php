<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Storage;

class Template extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'price',
        'views',
        'description',
        'content',
        'thumbnail',
    ];

    protected $attributes = [
        "views" => 0,
    ];

    public static function booted()
    {
        self::updated(function (Template $record): void {
            $directory = "templates";
            $key  = "thumbnail";
            if ($record->wasChanged($key)) {
                Storage::disk('s3')->delete("$directory/" . $record->getOriginal($key));
            }
        });

        self::deleted(function (Template $record): void {
            $directory = "templates";
            $key  = "thumbnail";
            Storage::disk('s3')->delete("$directory/" . $record[$key]);
        });
    }

    public function favorites_users() {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function collections_users()
    {
        return $this->belongsToMany(User::class, 'collections');
    }
}

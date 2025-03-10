<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'headline',
        'date',
        'content',
        'image',
    ];

    public static function booted()
    {
        self::updated(function (Article $record): void {
            $directory = "articles";
            $key  = "image";
            if ($record->wasChanged($key)) {
                Storage::disk('s3')->delete("$directory/" . $record->getOriginal($key));
            }
        });

        self::deleted(function (Article $record): void {
            $directory = "articles";
            $key  = "image";
            Storage::disk('s3')->delete("$directory/" . $record[$key]);
        });
    }
}

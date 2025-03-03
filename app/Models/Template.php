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
        'description',
        'file',
        'thumbnail',
    ];

    public static function booted()
    {
        self::deleted(function (Template $record): void {
            Storage::disk('s3')->delete("templates/$record->thumbnail");
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

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
        'file',
        'thumbnail',
    ];

    protected $attribues = [
        "views" => 0,
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

    public function favorites() {
        return $this->belongsToMany(User::class, 'favorite_templates');
    }
}

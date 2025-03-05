<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Storage;

class Ticket extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'type',
        'status',
        'image',
    ];

    public static function booted()
    {
        self::updated(function (Ticket $record): void {
            $directory = "tickets";
            $key  = "image";
            if ($record->wasChanged($key)) {
                Storage::disk('s3')->delete($directory . "/" . $record->getOriginal($key));
            }
        });

        self::deleted(function (Ticket $record): void {
            $directory = "tickets";
            $key  = "image";
            Storage::disk('s3')->delete($directory . "/" . $record[$key]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

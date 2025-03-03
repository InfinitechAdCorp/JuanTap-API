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
        'type',
        'subject',
        'description',
        'status',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function booted()
    {
        self::deleted(function (Ticket $record): void {
            Storage::disk('s3')->delete("tickets/$record->image");
        });
    }
}

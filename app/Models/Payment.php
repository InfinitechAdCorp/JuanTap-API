<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'template_id',
        'user_id',
        'remarks',
        'method',
        'status',
        'proof',
    ];

    protected $attributes = [
        'status' => 'Pending',
    ];

    public static function booted()
    {
        self::updated(function (Payment $record): void {
            $directory = "payments";
            $key  = "proof";
            if ($record->wasChanged($key)) {
                Storage::disk('s3')->delete("$directory/" . $record->getOriginal($key));
            }
        });

        self::deleted(function (Payment $record): void {
            $directory = "payments";
            $key  = "proof";
            Storage::disk('s3')->delete("$directory/" . $record[$key]);
        });
    }
}
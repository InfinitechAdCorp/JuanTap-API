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
        'user_id',
        'amount',
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
            $directory = "proofs";
            $key  = "proof";
            if ($record->wasChanged($key)) {
                Storage::disk('s3')->delete("$directory/" . $record->getOriginal($key));
            }
        });

        self::deleted(function (Payment $record): void {
            $directory = "proofs";
            $key  = "proof";
            Storage::disk('s3')->delete("$directory/" . $record[$key]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
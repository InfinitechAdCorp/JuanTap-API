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
        'number',
        'subject',
        'description',
        'status',
    ];

    protected $attributes = [
        'status' => 'Pending'
    ];

    public static function booted()
    {
        self::creating(function (Ticket $record): void {
            $randomNumber = mt_rand(000, 999);
            $record->number = str_pad($randomNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

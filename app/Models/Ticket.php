<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Ticket extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'number',
        'subject',
        'description',
    ];

    public static function booted()
    {
        self::creating(function (Ticket $record): void {
            $number = mt_rand(00000000, 99999999);
            $record->number = str_pad($number, 8, '0', STR_PAD_LEFT);
        });

        self::created(function (Ticket $record): void {
            $record->statuses()->create();
        });

        self::deleted(function (Ticket $record): void {
            $record->statuses()->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statuses() {
        return $this->hasMany(Status::class)->orderBy('created_at', 'desc');
    }
}

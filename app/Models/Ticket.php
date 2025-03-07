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
        'subject',
        'description',
        'status',
    ];

    protected $attributes = [
        'status' => 'Pending'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

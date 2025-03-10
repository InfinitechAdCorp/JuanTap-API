<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Status extends Model
{
    use HasFactory, HasUlids;
 
    protected $fillable = [
        'ticket_id',
        'status',
    ];

    protected $attributes = [
        'status' => 'Submitted'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

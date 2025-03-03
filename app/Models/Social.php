<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Social extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'profile_id',
        'platform',
        'url',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class CustomTemplate extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'profile_id',
        'background_color',
        'text_color',
        'font_family',
        'card_color',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}

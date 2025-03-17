<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class CustomTemplate extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'template_id',
        'user_id',
        'background_color',
        'text_color',
        'font_family',
        'card_color',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

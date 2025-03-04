<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class FavoriteTemplate extends Model
{
    use HasFactory, HasUlids;

    protected $table = "favorite_templates";

    protected $fillable = [
        'template_id',
        'user_id',
    ];
}

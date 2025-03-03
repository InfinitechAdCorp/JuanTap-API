<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class UserTemplate extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'template_id',
    ];
}

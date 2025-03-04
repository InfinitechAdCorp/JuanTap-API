<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class TemplateUser extends Model
{
    use HasFactory, HasUlids;

    protected $table = "template_user";

    protected $fillable = [
        'template_id',
        'user_id',
        'published',
        'favorite',
    ];

    protected $attributes = [
        'published' => 0,
        'favorite' => 0,
    ];
}

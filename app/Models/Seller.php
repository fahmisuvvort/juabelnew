<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'warehouse', 'user_id'
    ];

    protected $casts = [
        'warehouse' => 'object'
    ];
}

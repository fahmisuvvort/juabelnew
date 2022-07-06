<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    use HasFactory;

    protected $fillable = [
        'subdistrict_id',
        'subdistrict_name',
        'city_id',
        'city',
        'type',
        'province_id',
        'province',
    ];
}

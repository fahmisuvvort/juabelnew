<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];
    public $appends = ['src'];

    public function getSrcAttribute()
    {
        return url('/upload/images/' . $this->filename);
    }
}

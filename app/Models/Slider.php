<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'filepath', 'weight', 'filename'
    ];
    public $timestamps = false;
    public $appends = ['src'];

    public function getSrcAttribute()
    {
        return url('/upload/images/' . $this->filename);
    }
}

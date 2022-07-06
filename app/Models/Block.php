<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image? url('/upload/images/' . $this->image) : '';
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

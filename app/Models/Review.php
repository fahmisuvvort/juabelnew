<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'comment', 
        'rating', 
        'name'
    ];

    public $appends = [
        'created'
    ];

    public function getCreatedAttribute()
    {
        if($this->created_at > Carbon::now()->subWeek()) {
            return Carbon::parse($this->created_at)->diffForHumans();
        } else {
            return Carbon::parse($this->created_at)->format('d M Y');
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

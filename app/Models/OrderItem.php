<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $appends = ['created'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function getCreatedAttribute()
    {
        if($this->created_at >= Carbon::now()->subDays(2)){
            return Carbon::parse($this->created_at)->diffForHumans();
        } else {
            return 'Beberapa waktu lalu';
        }
    }
    public function images()
    {
        return $this->hasMany(Asset::class, 'assetable_id', 'product_id');
    }
}

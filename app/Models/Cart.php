<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'name',
        'sku',
        'price',
        'quantity',
        'weight',
        'image_url',
        'product_url',
        'product_stock',
        'product_id',
        'note',
        'seller'
    ];

    protected $casts = [
        'seller' => 'object'
    ];

    public function sumWeight()
    {
        return $this->weigth*$this->quantity;
    }
    public function sumPrice()
    {
        return $this->price*$this->quantity;
    }
}

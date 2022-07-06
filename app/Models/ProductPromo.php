<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPromo extends Model
{
    use HasFactory;
    protected $fillable = [
        'promo_id', 'product_id', 'discount_type', 'discount_amount'
    ];

    public function promoActive()
    {
        return $this->belongsTo(Promo::class, 'promo_id', 'id')->where('end_date', '>', now());
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVarian extends Model
{
    use HasFactory;
    protected $fillable = [
        'label',
        'value',
        'sku',
        'price',
        'stock',
        'has_subvarian',
        'varian_id',
    ];
    protected $casts = [
        'has_subvarian' => 'boolean'
    ];

    public function subvarian()
    {
        return $this->hasMany(ProductVarian::class, 'varian_id', 'id');
    }
}

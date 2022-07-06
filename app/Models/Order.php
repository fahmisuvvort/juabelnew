<?php

namespace App\Models;

use App\Models\Seller;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $appends = ['status_label', 'created', 'grand_total'];

    public $casts = [
        'created_at' => 'datetime:d/m/Y'
    ];

    public function getGrandTotalAttribute()
    {
        return $this->order_total+$this->payment_fee;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function getCreatedAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
        
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    public function getStatusLabelAttribute()
    {
        switch ($this->order_status) {
            case 'CANCELED':
                return 'Batal';
                break;
            case 'ISSUED':
                return 'Menunggu Konfirmasi';
                break;
            case 'PAID':
                return 'Lunas';
                break;
            case 'PROCESS':
                return 'Sedang Diproses';
                break;
            case 'SHIPPING':
                return 'Dikirim';
                break;
            case 'COMPLETE':
                return 'Selesai';
                break;
            
            default:
            return 'Belum Bayar';
                break;
        }

    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code', 'user_id', 'warung_id', 'total_price', 
        'status', 'snap_token', 'midtrans_transaction_id', 'payment_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warung()
    {
        return $this->belongsTo(Warung::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function warungRating()
    {
        return $this->hasOne(WarungRating::class);
    }

    public function menuRatings()
    {
        return $this->hasMany(MenuRating::class);
    }
}
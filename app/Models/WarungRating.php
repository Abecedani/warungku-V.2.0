<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarungRating extends Model
{
    protected $fillable = ['user_id', 'warung_id', 'order_id', 'rating', 'review'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warung()
    {
        return $this->belongsTo(Warung::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuRating extends Model
{
    protected $fillable = ['user_id', 'menu_id', 'order_id', 'rating', 'review'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warung extends Model
{

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'location_detail',
        'is_open',   
        'is_verified',
    ];

    protected $casts = [
        'is_open' => 'boolean',  
        'is_verified' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function categories()
    {
        return $this->hasMany(MenuCategory::class);
    }
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function ratings()
    {
        return $this->hasMany(WarungRating::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'warung_id',
        'menu_category_id',
        'name',
        'description',
        'price',
        'image',
        'status'
    ];

    public function warung()
    {
        return $this->belongsTo(Warung::class);
    }

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function categories()
    {
        return $this->belongsToMany(MenuCategory::class, 'menu_category_menu');
    }

    public function variants()
    {
        return $this->hasMany(MenuVariant::class);
    }


    public function images()
    {
        return $this->hasMany(MenuImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(MenuImage::class)->where('is_primary', true);
    }
    public function ratings()
    {
        return $this->hasMany(MenuRating::class);
    }
    public function getDisplayPriceAttribute()
    {
        return $this->variants->first()?->price ?? $this->price;
    }
}
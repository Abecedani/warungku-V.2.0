<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['nama', 'warna'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_tag');
    }
}
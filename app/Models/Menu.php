<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'warung_id', 'nama', 'deskripsi', 'harga',
        'foto', 'varian', 'tersedia',
    ];

    public function warung()
    {
        return $this->belongsTo(Warung::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'menu_tag');
    }
}
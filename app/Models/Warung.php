<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warung extends Model
{
    protected $fillable = [
        'user_id', 'nama', 'deskripsi', 'rating', 'status',
        'kategori', 'estimasi_waktu', 'foto', 'kontak',
        'area_kampus', 'alamat', 'jam_buka', 'jam_tutup',
        'status_verifikasi', 'catatan_verifikasi',
        'diverifikasi_oleh', 'diverifikasi_pada',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
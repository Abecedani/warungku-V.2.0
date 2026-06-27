<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\Warung;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Makanan', 'Minuman', 'Snack', 'Paket Hemat', 'Promo'];

        foreach ($categories as $name) {
            MenuCategory::firstOrCreate(
                ['name' => $name, 'warung_id' => null],
            );
        }
    }
}
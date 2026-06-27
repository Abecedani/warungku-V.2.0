<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warung;
use App\Models\Menu;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $warungCount = Warung::count();
        $menuCount = Menu::count();
        $mahasiswaCount = User::where('role', 'pembeli')->count();

        $warungPopuler = Warung::where('is_verified', true)
            ->where('is_open', true)
            ->take(3)
            ->get();

        $menuPopuler = Menu::whereHas('warung', fn($q) => $q->where('is_verified', true))
            ->whereDoesntHave('categories', fn($q) => $q->where('name', 'Promo'))
            ->where('status', 'tersedia')
            ->with(['images', 'warung', 'ratings'])
            ->take(4)
            ->get();

        $menuPromo = Menu::whereHas('warung', fn($q) => $q->where('is_verified', true))
            ->whereHas('categories', fn($q) => $q->where('name', 'Promo'))
            ->where('status', 'tersedia')
            ->with(['images', 'warung', 'ratings'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('welcome', compact(
            'warungCount',
            'menuCount',
            'mahasiswaCount',
            'warungPopuler',
            'menuPopuler',
            'menuPromo'
        ));
    }

    public function warung(Warung $warung)
    {
        $menus = $warung->menus()->where('status', 'tersedia')->with(['category', 'variants', 'images'])->get();
        $categories = $menus->pluck('category')->unique()->filter();
        return view('pembeli.warung-detail', compact('warung', 'menus', 'categories'));
    }
}
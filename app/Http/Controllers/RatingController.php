<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\WarungRating;
use App\Models\MenuRating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'menu_ratings' => 'required|array',
            'menu_ratings.*.rating' => 'required|integer|min:1|max:5',
            'menu_ratings.*.review' => 'nullable|string|max:500',
        ]);

        if ($order->user_id !== auth()->id() || $order->status !== 'selesai') {
            return back()->with('error', 'Tidak dapat memberikan ulasan.');
        }

        if (MenuRating::where('order_id', $order->id)->exists()) {
            return back()->with('error', 'Kamu sudah memberikan ulasan untuk pesanan ini.');
        }

        foreach ($request->menu_ratings as $menuId => $data) {
            MenuRating::create([
                'user_id' => auth()->id(),
                'menu_id' => $menuId,
                'order_id' => $order->id,
                'rating' => $data['rating'],
                'review' => $data['review'] ?? null,
            ]);
        }

        $order->load('warung');
        $avgRating = MenuRating::whereHas(
            'menu',
            fn($q) =>
            $q->where('warung_id', $order->warung_id)
        )->avg('rating');

        $order->warung->update(['rating' => round($avgRating, 1)]);

        return back()->with('success', 'Ulasan berhasil dikirim!');
    }
}
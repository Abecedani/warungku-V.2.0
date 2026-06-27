<?php

namespace App\Http\Controllers;

use App\Models\Order;

class PembeliController extends Controller
{
    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['warung', 'orderItems.menu.images', 'warungRating'])
            ->latest()
            ->get();


        return view('pembeli.orders', compact('orders'));
    }

}
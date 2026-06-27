<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $warung = auth()->user()->warung;
        $orders = $warung->orders()
            ->with(['user', 'orderItems.menu.images', 'orderItems.menu.ratings', 'orderItems.variant', 'menuRatings.menu'])
            ->latest()
            ->get();

        return view('warungs.pesanan', compact('warung', 'orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,dibayar,diproses,siap_diambil,selesai,dibatalkan'
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diupdate!');
    }

}
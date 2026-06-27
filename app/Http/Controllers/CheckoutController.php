<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        
        $carts = auth()->user()->carts()->with('menu.warung', 'variant')->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        $total = $carts->sum(fn($c) => ($c->variant ? $c->variant->price : $c->menu->price) * $c->quantity);
        $warung = $carts->first()->menu->warung;
        $ewallets = ['GoPay', 'OVO', 'Dana', 'ShopeePay', 'LinkAja'];

        return view('pembeli.checkout', compact('carts', 'total', 'warung', 'ewallets'));
    }

    public function buyNow(Request $request)
    {
        $request->validate(['menu_id' => 'required|exists:menus,id']);

        $menu = Menu::with(['warung', 'variants'])->findOrFail($request->menu_id);
        $variant = $request->variant_id ? $menu->variants->find($request->variant_id) : null;
        $price = $variant ? $variant->price : $menu->price;
        $ewallets = ['GoPay', 'OVO', 'Dana', 'ShopeePay', 'LinkAja'];

        return view('pembeli.checkout-now', compact('menu', 'variant', 'price', 'ewallets'));
    }

    public function processNow(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'payment_type' => 'required|string',
        ]);

        $menu = Menu::with('warung')->findOrFail($request->menu_id);
        $variant = $request->variant_id ? \App\Models\MenuVariant::find($request->variant_id) : null;
        $price = $variant ? $variant->price : $menu->price;

        $order = Order::create([
            'order_code' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id' => auth()->id(),
            'warung_id' => $menu->warung_id,
            'total_price' => $price,
            'status' => 'dibayar',
            'payment_type' => $request->payment_type,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'variant_id' => $variant?->id,
            'quantity' => 1,
            'price' => $price,
        ]);

        return redirect()->route('pembeli.orders')->with('success', 'Pesanan berhasil dibuat!');
    }


    // Proses bayar dari keranjang
    public function process(Request $request)
    {
        $request->validate([
            'payment_type' => 'required|string',
        ]);

        $carts = auth()->user()->carts()->with('menu.warung', 'variant')->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        // Kelompokkan per warung
        $grouped = $carts->groupBy(fn($c) => $c->menu->warung_id);

        foreach ($grouped as $warungId => $items) {
            $total = $items->sum(fn($c) => ($c->variant ? $c->variant->price : $c->menu->price) * $c->quantity);

            $order = Order::create([
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => auth()->id(),
                'warung_id' => $warungId,
                'total_price' => $total,
                'status' => 'dibayar',
                'payment_type' => $request->payment_type,
            ]);

            foreach ($items as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $cart->menu_id,
                    'variant_id' => $cart->variant_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->variant ? $cart->variant->price : $cart->menu->price,
                ]);
            }
        }

        auth()->user()->carts()->delete();

        return redirect()->route('pembeli.orders')->with('success', 'Pesanan berhasil dibuat!');
    }

}
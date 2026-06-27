<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Menu $menu, Request $request)
    {
        if ($menu->status !== 'tersedia') {
            return back()->with('error', 'Menu ini sedang tidak tersedia.');
        }

        $existingWarungId = auth()->user()->carts()
            ->with('menu')
            ->get()
            ->first()?->menu?->warung_id;

        if ($existingWarungId && $existingWarungId !== $menu->warung_id) {
            return back()->with('error', 'Keranjangmu berisi menu dari warung lain. Kosongkan dulu ya!');
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'menu_id' => $menu->id, 'variant_id' => $request->variant_id ?? null],
            ['quantity' => 0]
        );
        $cart->increment('quantity');

        return back()->with('success', 'Menu ditambahkan ke keranjang!');
    }

    public function decrease(Menu $menu, Request $request)
    {
        $cart = Cart::where('user_id', auth()->id())
            ->where('menu_id', $menu->id)
            ->where('variant_id', $request->variant_id ?? null)
            ->first();

        if ($cart) {
            if ($cart->quantity <= 1) {
                $cart->delete();
            } else {
                $cart->decrement('quantity');
            }
        }

        return back();
    }

    public function index()
    {
        $cartItems = auth()->user()->carts()
            ->with('menu.warung', 'variant')
            ->get();

        $warung = $cartItems->first()?->menu?->warung;
        $total = $cartItems->sum(fn($c) => $c->quantity * ($c->variant ? $c->variant->price : $c->menu->price));

        return view('pembeli.cart', compact('cartItems', 'warung', 'total'));
    }

    public function remove(Menu $menu, Request $request)
    {
        $cart = Cart::where('user_id', auth()->id())
            ->where('menu_id', $menu->id)
            ->where('variant_id', $request->variant_id ?? null)
            ->first();

        if ($cart) {
            $cart->delete();
        } else {
            return back()->with('error', 'Item tidak ditemukan di keranjang.');
        }

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        auth()->user()->carts()->delete();
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuVariant;
use App\Models\MenuImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $warung = auth()->user()->warung;
        $menus = $warung->menus()->with(['categories', 'variants', 'images'])->latest()->get();
        $categories = MenuCategory::where(function ($q) use ($warung) {
            $q->whereNull('warung_id')
                ->orWhere('warung_id', $warung->id);
        })->get();

        return view('warungs.menu', compact('warung', 'menus', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:tersedia,habis',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:menu_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'variant_names' => 'nullable|array',
            'variant_names.*' => 'string|max:255',
            'variant_prices' => 'nullable|array',
            'variant_prices.*' => 'integer|min:0',
        ]);

        $menu = auth()->user()->warung->menus()->create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        // Kategori
        if ($request->category_ids) {
            $menu->categories()->sync($request->category_ids);
        }

        // Foto multiple
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('menus', 'public');
                $menu->images()->create([
                    'path' => $path,
                    'is_primary' => $i === 0,
                ]);
            }
        }

        // Varian
        if ($request->variant_names) {
            foreach ($request->variant_names as $i => $name) {
                if ($name && isset($request->variant_prices[$i])) {
                    $menu->variants()->create([
                        'name' => $name,
                        'price' => $request->variant_prices[$i],
                    ]);
                }
            }
        }

        return back()->with('success', 'Menu berhasil ditambahkan!');
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:tersedia,habis',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:menu_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'variant_names' => 'nullable|array',
            'variant_names.*' => 'string|max:255',
            'variant_prices' => 'nullable|array',
            'variant_prices.*' => 'integer|min:0',
        ]);

        $menu->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        // Kategori
        $menu->categories()->sync($request->category_ids ?? []);

        // Foto baru
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('menus', 'public');
                $menu->images()->create([
                    'path' => $path,
                    'is_primary' => $menu->images()->count() === 0 && $i === 0,
                ]);
            }
        }

        // Reset varian lama, simpan yang baru
        $menu->variants()->delete();
        if ($request->variant_names) {
            foreach ($request->variant_names as $i => $name) {
                if ($name && isset($request->variant_prices[$i])) {
                    $menu->variants()->create([
                        'name' => $name,
                        'price' => $request->variant_prices[$i],
                    ]);
                }
            }
        }

        return back()->with('success', 'Menu berhasil diupdate!');
    }

    public function destroyImage(MenuImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return back()->with('success', 'Foto berhasil dihapus!');
    }

    public function destroy(Menu $menu)
    {
        foreach ($menu->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $menu->images()->delete();
        $menu->variants()->delete();
        $menu->categories()->detach();
        $menu->delete();

        return back()->with('success', 'Menu berhasil dihapus!');
    }
}
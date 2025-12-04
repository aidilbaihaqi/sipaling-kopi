<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Get stock list with filters
     */
    public function index(Request $request)
    {
        $query = Menu::with('category');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by availability
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('is_available', true)->where('stock', '>', 0);
            } elseif ($request->availability === 'unavailable') {
                $query->where(function ($q) {
                    $q->where('is_available', false)->orWhere('stock', '<=', 0);
                });
            }
        }

        $menus = $query->orderBy('name')->get();
        $categories = Category::all();

        return response()->json([
            'status' => 'success',
            'total' => $menus->count(),
            'data' => [
                'menus' => $menus,
                'categories' => $categories
            ]
        ]);
    }

    /**
     * Toggle menu availability
     */
    public function toggleAvailability(Menu $menu)
    {
        if ($menu->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak bisa tandai tersedia karena stok habis!'
            ], 400);
        }

        $menu->is_available = !$menu->is_available;
        $menu->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status ketersediaan menu berhasil diubah!',
            'data' => $menu
        ]);
    }

    /**
     * Update menu stock
     */
    public function updateStock(Request $request, Menu $menu)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $menu->stock = $request->stock;
        $menu->is_available = $menu->stock > 0;
        $menu->save();

        return response()->json([
            'status' => 'success',
            'message' => "Stok menu '{$menu->name}' berhasil diperbarui!",
            'data' => $menu
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Get all menus
     */
    public function index(Request $request)
    {
        $query = Menu::with('category');

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by availability
        if ($request->filled('is_available')) {
            $query->where('is_available', $request->is_available === 'true');
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $menus = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'total' => $menus->count(),
            'data' => $menus
        ]);
    }

    /**
     * Get single menu
     */
    public function show(Menu $menu)
    {
        return response()->json([
            'status' => 'success',
            'data' => $menu->load('category')
        ]);
    }

    /**
     * Create new menu
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
        ]);

        $menu = Menu::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Menu berhasil ditambahkan',
            'data' => $menu->load('category')
        ], 201);
    }

    /**
     * Update menu
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'is_available' => 'boolean',
        ]);

        $menu->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Menu berhasil diupdate',
            'data' => $menu->fresh()->load('category')
        ]);
    }

    /**
     * Delete menu
     */
    public function destroy(Menu $menu)
    {
        $name = $menu->name;
        $menu->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Menu '{$name}' berhasil dihapus"
        ]);
    }

    /**
     * Get available menus for cashier
     */
    public function available()
    {
        $menus = Menu::with('category')
            ->where('stock', '>', 0)
            ->where('is_available', true)
            ->orderBy('category_id')
            ->get();

        return response()->json([
            'status' => 'success',
            'total' => $menus->count(),
            'data' => $menus
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories
     */
    public function index()
    {
        $categories = Category::withCount('menus')->get();

        return response()->json([
            'status' => 'success',
            'total' => $categories->count(),
            'data' => $categories
        ]);
    }

    /**
     * Get single category
     */
    public function show(Category $category)
    {
        return response()->json([
            'status' => 'success',
            'data' => $category->loadCount('menus')
        ]);
    }

    /**
     * Create new category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category
        ], 201);
    }

    /**
     * Update category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diupdate',
            'data' => $category->fresh()
        ]);
    }

    /**
     * Delete category
     */
    public function destroy(Category $category)
    {
        $name = $category->name;
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Kategori '{$name}' berhasil dihapus"
        ]);
    }
}

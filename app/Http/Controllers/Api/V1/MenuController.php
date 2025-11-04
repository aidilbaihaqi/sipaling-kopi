<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        return response()->json(Menu::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:menus|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'is_available' => 'required|boolean',
            'stock' => 'required|integer',
        ]);

        $menu = Menu::create($request->all());

        return response()->json($menu, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return response()->json($menu);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,' . $menu->id . '|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'is_available' => 'required|boolean',
            'stock' => 'required|integer',
        ]);

        $menu->update($request->all());

        return response()->json($menu);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json(null, 204);
    }
}

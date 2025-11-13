<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.menu')
            ->whereIn('status', ['pending', 'processing', 'ready'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kitchen.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready,cancelled',
        ]);

        $newStatus = $request->status;

        if ($order->status !== 'ready' && $newStatus === 'ready') {
            foreach ($order->items as $item) {
                $menu = $item->menu;
                if ($menu->stock < $item->quantity) {
                    return redirect()->back()->with('error', "Stok menu '{$menu->name}' tidak cukup untuk menyelesaikan pesanan.");
                }
            }

            foreach ($order->items as $item) {
                $menu = $item->menu;
                $menu->stock = max(0, $menu->stock - $item->quantity);
                $menu->is_available = $menu->stock > 0;
                $menu->save();
            }
        }

        $order->status = $newStatus;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diubah!');
    }

    public function stock(Request $request)
    {
        $query = Menu::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

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

        return view('kitchen.stock', [
            'menus' => $menus,
            'search' => $request->search ?? '',
            'availability' => $request->availability ?? '',
        ]);
    }

    public function toggleMenuAvailability(Menu $menu)
    {
        if ($menu->stock <= 0) {
            return redirect()->back()->with('error', 'Tidak bisa tandai tersedia karena stok habis!');
        }

        $menu->is_available = !$menu->is_available;
        $menu->save();

        return redirect()->back()->with('success', 'Status ketersediaan menu berhasil diubah!');
    }

    public function updateStock(Request $request, Menu $menu)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $menu->stock = $request->stock;
        $menu->is_available = $menu->stock > 0;
        $menu->save();

        return redirect()->back()->with('success', "Stok menu '{$menu->name}' berhasil diperbarui!");
    }
}

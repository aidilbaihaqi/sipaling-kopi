<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Get all orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'details.menu']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter today only
        if ($request->filled('today') && $request->today === 'true') {
            $query->whereDate('created_at', today());
        }

        $orders = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'total' => $orders->count(),
            'data' => $orders
        ]);
    }

    /**
     * Get single order
     */
    public function show(Order $order)
    {
        return response()->json([
            'status' => 'success',
            'data' => $order->load(['user', 'details.menu', 'cashier'])
        ]);
    }

    /**
     * Create new order (Cashier checkout)
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:50',
            'type' => 'required|in:dine-in,takeaway',
            'table_no' => 'required_if:type,dine-in',
            'payment_method' => 'required|in:cash,qris,transfer',
            'cart' => 'required|array',
            'cart.*.menu_id' => 'required|exists:menus,id',
            'cart.*.qty' => 'required|integer|min:1',
            'cart.*.note' => 'nullable|string',
            'payment_amount' => 'required|numeric|min:0',
            'total_price' => 'required|numeric',
        ]);

        if ($request->payment_method == 'cash' && $request->payment_amount < $request->total_price) {
            return response()->json([
                'status' => 'error',
                'message' => 'Uang pembayaran kurang!'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'order_number' => 'ORD-' . time(),
                'customer_name' => $request->customer_name,
                'type' => $request->type,
                'table_no' => $request->type == 'dine-in' ? $request->table_no : null,
                'total_amount' => $request->total_price,
                'payment_amount' => $request->payment_amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'user_id' => Auth::id(),
            ]);

            foreach ($request->cart as $item) {
                $menu = Menu::lockForUpdate()->find($item['menu_id']);

                if (!$menu || $menu->stock < $item['qty'] || !$menu->is_available) {
                    throw new \Exception("Stok menu '{$menu->name}' tidak cukup atau sedang tidak tersedia.");
                }

                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $item['qty'],
                    'price' => $menu->price,
                    'note' => $item['note'] ?? null,
                ]);

                $menu->decrement('stock', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil!',
                'data' => $order->load(['details.menu', 'cashier'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi gagal: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update order status (Kitchen)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled',
        ]);

        $newStatus = $request->status;

        // If changing to 'ready', check and reduce stock
        if ($order->status !== 'ready' && $newStatus === 'ready') {
            foreach ($order->items as $item) {
                $menu = $item->menu;
                if ($menu->stock < $item->quantity) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Stok menu '{$menu->name}' tidak cukup untuk menyelesaikan pesanan."
                    ], 400);
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

        return response()->json([
            'status' => 'success',
            'message' => 'Status pesanan berhasil diubah!',
            'data' => $order->fresh()->load(['items.menu'])
        ]);
    }

    /**
     * Get orders for kitchen (pending, processing, ready)
     */
    public function kitchen()
    {
        $orders = Order::with('items.menu')
            ->whereIn('status', ['pending', 'processing', 'ready'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'total' => $orders->count(),
            'data' => $orders
        ]);
    }

    /**
     * Get today's orders for cashier history
     */
    public function history()
    {
        $orders = Order::with(['details.menu'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'total' => $orders->count(),
            'data' => $orders
        ]);
    }
}

<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function index()
    {
        $menus = Menu::where('stock', '>', 0)
                     ->where('is_available', true) 
                     ->orderBy('category_id')
                     ->get();

        return view('cashier.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:50',
            'type'          => 'required|in:dine-in,takeaway',
            'table_no'      => 'required_if:type,dine-in',
            'payment_method'=> 'required|in:cash,qris,transfer',
            'cart'          => 'required|array',
            'payment_amount'=> 'required|numeric|min:0',
            'total_price'   => 'required|numeric',
        ]);

        if ($request->payment_method == 'cash' && $request->payment_amount < $request->total_price) {
            return back()->with('error', 'Uang pembayaran kurang!');
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'order_number'   => 'ORD-' . time(),
                'customer_name'  => $request->customer_name,
                'type'           => $request->type,
                'table_no'       => $request->type == 'dine-in' ? $request->table_no : null,
                'total_amount'   => $request->total_price,
                'payment_amount' => $request->payment_amount,
                'payment_method' => $request->payment_method,
                'status'         => 'pending',
                'user_id'        => Auth::id(),
            ]);

            foreach ($request->cart as $itemId => $details) {
                $menu = Menu::lockForUpdate()->find($itemId); 

                if (!$menu || $menu->stock < $details['qty'] || !$menu->is_available) {
                    throw new \Exception("Stok menu '{$menu->name}' tidak cukup atau sedang tidak tersedia.");
                }

                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id'  => $menu->id,
                    'quantity' => $details['qty'],
                    'price'    => $menu->price,
                    'note'     => $details['note'] ?? null,
                ]);

                $menu->decrement('stock', $details['qty']);
            }

            DB::commit();
            
            return redirect()->route('cashier.print', $order->id)->with('success', 'Transaksi Berhasil!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Transaksi Gagal: ' . $e->getMessage());
        }
    }

    public function print($id)
    {
        $order = Order::with(['details.menu', 'cashier'])->findOrFail($id);
        return view('cashier.print', compact('order'));
    }

    public function history()
    {
        $orders = Order::whereDate('created_at', today())
                       ->orderBy('created_at', 'desc')
                       ->get();
        
        return view('cashier.history', compact('orders'));
    }
}
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with(['orderItems.menu', 'payment'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:dine_in,takeaway',
            'table_no' => 'nullable|integer',
            'status' => 'required|in:PENDING,IN_PROGRESS,COMPLETED',
            'total_price' => 'required|numeric',
        ]);

        $order = Order::create($request->all());

        return response()->json($order, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return response()->json($order->load(['orderItems.menu', 'payment']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'type' => 'required|in:dine_in,takeaway',
            'table_no' => 'nullable|integer',
            'status' => 'required|in:PENDING,IN_PROGRESS,COMPLETED',
            'total_price' => 'required|numeric',
        ]);

        $order->update($request->all());

        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }
}

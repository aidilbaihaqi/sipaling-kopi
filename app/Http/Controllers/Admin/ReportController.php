<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $orders = Order::with('user', 'items.menu')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->latest()
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        
        $paymentMethods = $orders->groupBy('payment_method')->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('total_amount')
            ];
        });

        return view('admin.reports.index', compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'paymentMethods',
            'startDate',
            'endDate'
        ));
    }
}

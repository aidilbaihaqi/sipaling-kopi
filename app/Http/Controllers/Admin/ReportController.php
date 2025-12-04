<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Ambil semua order (tidak hanya completed) untuk demo
        // Ubah whereIn jika hanya ingin status tertentu
        $orders = Order::with(['user', 'details.menu'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('status', ['completed', 'ready', 'processing', 'pending'])
            ->latest()
            ->get();

        // Data sudah ada di database dari OrderSeeder
        // Tidak perlu dummy data lagi

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        
        // Hitung rata-rata per transaksi
        $averagePerTransaction = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
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
            'averagePerTransaction',
            'paymentMethods',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export laporan ke Excel
     * 
     * Method ini akan download file Excel dengan data laporan
     * sesuai filter tanggal yang dipilih
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Ambil data orders sesuai filter
        $orders = Order::with(['user', 'details.menu'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('status', ['completed', 'ready', 'processing', 'pending'])
            ->latest()
            ->get();

        // Generate filename dengan tanggal
        $filename = 'Laporan_Penjualan_' . $startDate . '_to_' . $endDate . '.xlsx';

        // Download Excel
        return Excel::download(new ReportExport($orders, $startDate, $endDate), $filename);
    }
}

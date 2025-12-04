<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $orders;
    protected $startDate;
    protected $endDate;

    public function __construct($orders, $startDate, $endDate)
    {
        $this->orders = $orders;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Return collection of orders
     */
    public function collection()
    {
        return $this->orders;
    }

    /**
     * Define headings for Excel
     */
    public function headings(): array
    {
        return [
            'No. Order',
            'Tanggal',
            'Customer',
            'Items',
            'Metode Pembayaran',
            'Total (Rp)',
        ];
    }

    /**
     * Map data for each row
     */
    public function map($order): array
    {
        // Gabungkan semua items menjadi string
        $items = $order->items->map(function($item) {
            return $item->menu->name . ' (' . $item->quantity . 'x)';
        })->implode(', ');

        return [
            $order->order_number,
            $order->created_at->format('d/m/Y H:i'),
            $order->customer_name,
            $items,
            ucfirst($order->payment_method),
            number_format($order->total_amount, 0, ',', '.'),
        ];
    }

    /**
     * Style the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '78350f'], // Amber-900
                ],
            ],
        ];
    }

    /**
     * Set sheet title
     */
    public function title(): string
    {
        return 'Laporan Penjualan';
    }
}

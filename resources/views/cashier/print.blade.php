<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ $order->order_number ?? $order->id }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 300px; font-size: 12px; margin: 0; padding: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .border-b { border-bottom: 1px dashed #000; margin: 5px 0; }
        .flex { display: flex; justify-content: space-between; }
        .mt-2 { margin-top: 10px; }
        .note { font-size: 10px; color: #666; font-style: italic; }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center">
        <div class="bold" style="font-size: 16px;">SIPALING KOPI</div>
        <div>Jl. Kopi Nikmat No. 1</div>
        <div>--------------------------------</div>
    </div>

    <div class="flex">
        <span>No: #{{ $order->order_number ?? $order->id }}</span>
        <span>{{ $order->created_at->format('d/m/y H:i') }}</span>
    </div>
    <div class="border-b"></div>
    <div>Plg: {{ $order->customer_name }}</div>
    <div>Kasir: {{ $order->cashier->name ?? 'Staff' }}</div>
    <div>Tipe: {{ $order->type == 'dine-in' ? 'Dine In' : 'Take Away' }}</div>
    @if($order->type == 'dine-in' && $order->table_no)
    <div>Meja: {{ $order->table_no }}</div>
    @endif
    <div>Metode: {{ strtoupper($order->payment_method ?? 'CASH') }}</div>
    <div class="border-b"></div>

    @foreach($order->details as $detail)
    <div style="margin-bottom: 2px;">
        <div class="bold">{{ $detail->menu->name }}</div>
        <div class="flex">
            <span>{{ $detail->quantity }} x {{ number_format($detail->price, 0, ',', '.') }}</span>
            <span>{{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}</span>
        </div>
        @if($detail->note)
        <div class="note">📝 {{ $detail->note }}</div>
        @endif
    </div>
    @endforeach

    <div class="border-b"></div>

    <div class="flex bold">
        <span>TOTAL</span>
        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
    </div>
    <div class="flex">
        <span>Bayar</span>
        <span>Rp {{ number_format($order->payment_amount, 0, ',', '.') }}</span>
    </div>
    <div class="flex">
        <span>Kembali</span>
        <span>Rp {{ number_format($order->payment_amount - $order->total_amount, 0, ',', '.') }}</span>
    </div>

    <div class="border-b"></div>
    <div class="text-center mt-2">
        Terima Kasih!<br>
        Ngopi Dulu Biar Gak Pusing
    </div>
    
    <!-- Tombol Aksi (Tidak Tercetak) -->
    <div class="text-center mt-2 no-print" style="padding: 15px; background: #f9f9f9; border-top: 2px solid #ddd;">
        <button onclick="window.print()" style="background: #78350f; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; margin-right: 10px;">
            🖨️ Cetak Struk
        </button>
        <a href="{{ route('cashier.index') }}" style="text-decoration: none; background: #6b7280; color: white; padding: 10px 20px; border-radius: 5px; display: inline-block;">
            ← Kembali ke Kasir
        </a>
    </div>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
        
        button:hover {
            opacity: 0.9;
        }
    </style>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir - Sipaling Kopi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/api.js'])
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-100 h-screen overflow-hidden flex flex-col">

    <nav class="bg-amber-700 shadow-md h-16 flex items-center justify-between px-6 z-20">
        <div class="flex items-center gap-3">
            <div class="bg-white p-1.5 rounded-lg shadow-sm">
                <span class="text-xl">☕</span>
            </div>
            <div>
                <h1 class="font-bold text-lg text-white leading-tight">Sipaling Kopi</h1>
                <p class="text-xs text-amber-200">Point of Sales System</p>
            </div>
        </div>
        
        <div class="flex items-center gap-6">
            <a href="{{ route('cashier.history') }}" class="text-amber-100 hover:text-white font-medium text-sm flex items-center gap-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat
            </a>
            <div class="h-8 w-px bg-amber-600"></div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-bold text-white">{{ Auth::user()->name ?? 'Kasir' }}</p>
                    <p class="text-xs text-amber-200">Staff on Duty</p>
                </div>
                <button onclick="handleLogout()" class="bg-amber-800 hover:bg-amber-900 text-white px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm border border-amber-600">
                    LOGOUT
                </button>
            </div>
        </div>
    </nav>

    <div class="flex-1 overflow-hidden relative">
        @yield('content')
    </div>

    <script>
    async function handleLogout() {
        try {
            await authApi.logout();
            window.location.href = '/login';
        } catch (error) {
            window.location.href = '/login';
        }
    }
    </script>
</body>
</html>

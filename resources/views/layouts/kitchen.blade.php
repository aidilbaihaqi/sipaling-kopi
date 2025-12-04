<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dapur - Sipaling Kopi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/api.js']) 
</head>
<body class="bg-gray-100 min-h-screen font-sans"> 

    <nav class="bg-amber-900 text-white px-6 py-4 flex justify-between items-center shadow-lg">
        <h1 class="text-2xl font-bold flex items-center gap-3 tracking-wide">
            <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Dapur - Sipaling Kopi
        </h1>
        <div class="flex items-center space-x-4">
            <a href="{{ route('kitchen.index') }}" class="px-4 py-2 rounded-lg transition {{ request()->routeIs('kitchen.index') ? 'bg-amber-700 font-bold shadow' : 'hover:bg-amber-800' }}">Antrian</a>
            <a href="{{ route('kitchen.stock') }}" class="px-4 py-2 rounded-lg transition {{ request()->routeIs('kitchen.stock') ? 'bg-amber-700 font-bold shadow' : 'hover:bg-amber-800' }}">Stok Menu</a>
            <button onclick="handleLogout()" class="bg-red-600 text-white font-medium px-4 py-2 rounded-lg hover:bg-red-700 transition shadow">Logout</button>
        </div>
    </nav>

    <main class="p-6 md:p-8 max-w-7xl mx-auto">
        @yield('content')
    </main>

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

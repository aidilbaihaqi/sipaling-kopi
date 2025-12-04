<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sipaling Kopi - Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/api.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-amber-900 to-amber-950 text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold">☕ Sipaling Kopi</h1>
                <p class="text-sm text-amber-200">Admin Panel</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-amber-800 {{ request()->routeIs('admin.dashboard') ? 'bg-amber-800 border-l-4 border-amber-400' : '' }}">
                    <span class="mr-3">📊</span> Dashboard
                </a>
                <a href="{{ route('menus.index') }}" class="flex items-center px-6 py-3 hover:bg-amber-800 {{ request()->routeIs('menus.*') ? 'bg-amber-800 border-l-4 border-amber-400' : '' }}">
                    <span class="mr-3">☕</span> Menu
                </a>
                <a href="{{ route('categories.index') }}" class="flex items-center px-6 py-3 hover:bg-amber-800 {{ request()->routeIs('categories.*') ? 'bg-amber-800 border-l-4 border-amber-400' : '' }}">
                    <span class="mr-3">📁</span> Kategori
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center px-6 py-3 hover:bg-amber-800 {{ request()->routeIs('users.*') ? 'bg-amber-800 border-l-4 border-amber-400' : '' }}">
                    <span class="mr-3">👥</span> Pengguna
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center px-6 py-3 hover:bg-amber-800 {{ request()->routeIs('admin.reports') ? 'bg-amber-800 border-l-4 border-amber-400' : '' }}">
                    <span class="mr-3">📈</span> Laporan
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ auth()->user()->name }}</span>
                        <button onclick="handleLogout()" class="px-4 py-2 bg-amber-900 text-white rounded hover:bg-amber-800">
                            Logout
                        </button>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </main>
        </div>
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

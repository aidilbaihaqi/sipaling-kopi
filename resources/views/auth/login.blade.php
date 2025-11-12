<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sipaling Kopi</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gradient-to-br from-amber-900 via-amber-950 to-stone-900 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-amber-900">☕ Sipaling Kopi</h1>
                <p class="text-gray-600 mt-2">Admin Panel</p>
            </div>

            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
                </div>

                <div class="mb-6 relative">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    {{-- Tombol Toggle Mata (Show/Hide) --}}
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 top-7 pr-3 flex items-center text-sm leading-5 focus:outline-none">
                        {{-- Icon SVG Mata Terbuka (Default) --}}
                        <svg id="eye-open" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: block;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        {{-- Icon SVG Mata Tertutup --}}
                        <svg id="eye-closed" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.823A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7a9.97 9.97 0 011.875.177M2.458 12C3.732 7.943 7.523 5 12 5c.677 0 1.332.079 1.97.234"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-2-2m-7-7l2 2"></path>
                        </svg>
                    </button>
                </div>

                <button type="submit" class="w-full bg-amber-900 text-white py-3 rounded-lg hover:bg-amber-800 font-semibold transition">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>

</html>
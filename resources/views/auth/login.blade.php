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

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
                </div>

                <button type="submit" class="w-full bg-amber-900 text-white py-3 rounded-lg hover:bg-amber-800 font-semibold transition">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>

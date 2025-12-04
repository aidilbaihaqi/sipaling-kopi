<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sipaling Kopi</title>
    @vite(['resources/css/app.css', 'resources/js/api.js'])

    <style>
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-amber-900 via-amber-950 to-stone-900 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-amber-900">☕ Sipaling Kopi</h1>
                <p class="text-gray-600 mt-2">Cafe Management System</p>
            </div>

            <!-- Error Messages -->
            <div id="errorAlert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden">
                <p id="errorMessage"></p>
            </div>

            <!-- Login Form -->
            <form id="loginForm">
                <!-- Email Field -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                </div>

                <!-- Password Field -->
                <div class="mb-6 relative">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">

                    <!-- Toggle Password Visibility -->
                    <button type="button" id="togglePassword"
                        class="absolute inset-y-0 right-0 top-7 pr-3 flex items-center focus:outline-none">
                        <svg id="eye-icon"
                            class="h-5 w-5 text-gray-300 cursor-pointer transition-colors duration-150"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 4.5 12 4.5
                                   c4.638 0 8.573 3.008 9.963 7.178.07.207.07.431 0 .638
                                   C20.577 16.49 16.64 19.5 12 19.5
                                   c-4.638 0-8.573-3.008-9.964-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn"
                    class="w-full bg-amber-900 text-white py-3 rounded-lg hover:bg-amber-800 font-semibold transition duration-200 transform hover:scale-[1.02]">
                    Login
                </button>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const toggleBtn = document.getElementById('togglePassword');
        const pwdInput = document.getElementById('password');
        const eye = document.getElementById('eye-icon');

        toggleBtn.addEventListener('click', () => {
            const isHidden = pwdInput.type === 'password';
            pwdInput.type = isHidden ? 'text' : 'password';
            eye.classList.toggle('text-gray-300', !isHidden);
            eye.classList.toggle('text-gray-700', isHidden);
        });

        // Login form submission via API
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const submitBtn = document.getElementById('submitBtn');
            const errorAlert = document.getElementById('errorAlert');
            const errorMessage = document.getElementById('errorMessage');

            // Disable button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Loading...';
            errorAlert.classList.add('hidden');

            try {
                const response = await authApi.login(email, password);
                
                if (response.status === 'success') {
                    // Redirect based on role
                    window.location.href = response.data.redirect;
                }
            } catch (error) {
                errorAlert.classList.remove('hidden');
                errorMessage.textContent = error.data?.message || 'Email atau password salah';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Login';
            }
        });
    </script>
</body>
</html>

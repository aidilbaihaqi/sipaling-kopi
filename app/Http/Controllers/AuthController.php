<?php

namespace App\Http\Controllers;

/**
 * ============================================
 * AUTHENTICATION CONTROLLER
 * ============================================
 * 
 * Controller untuk menangani proses authentication
 * Mendukung session-based auth (web) dan API token (Sanctum)
 * 
 * @package  App\Http\Controllers
 * @version  2.0.0
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show Login Form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Process Login (Web Session + API Token)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Create API token for frontend API calls
            $token = $user->createToken('web-session')->plainTextToken;

            // If AJAX/API request, return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                        'redirect' => $this->getRedirectUrl($user->role)
                    ]
                ]);
            }

            // Regular form submission - redirect
            return redirect()->intended($this->getRedirectUrl($user->role));
        }

        // Login failed
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah'
            ], 401);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        // Revoke all tokens
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Logout berhasil'
            ]);
        }

        return redirect('/login');
    }

    /**
     * Get redirect URL based on role
     */
    private function getRedirectUrl($role)
    {
        return match($role) {
            'admin' => '/admin/dashboard',
            'cashier' => '/cashier',
            'kitchen' => '/kitchen',
            default => '/login'
        };
    }
}

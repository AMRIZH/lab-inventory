<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    private $supabaseUrl;
    private $supabaseKey;

    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_URL');
        $this->supabaseKey = env('SUPABASE_KEY');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $response = Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'apikey' => $this->supabaseKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->supabaseUrl}/auth/v1/token?grant_type=password", [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            Session::put('supabase_token', $data['access_token']);
            return redirect('/dashboard');
        } else {
            return back()->with('error', 'Email atau password salah');
        }
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Get the redirect URL from environment or use default
        $redirectUrl = env('SUPABASE_EMAIL_CONFIRM_REDIRECT_URL', env('APP_URL') . '/auth/confirm');
        
        $response = Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'apikey' => $this->supabaseKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->supabaseUrl}/auth/v1/signup", [
            'email' => $request->email,
            'password' => $request->password,
            'options' => [
                'emailRedirectTo' => $redirectUrl
            ]
        ]);

        if ($response->successful()) {
            return redirect()->route('login')->with('success', 'Berhasil daftar! Silakan cek email untuk konfirmasi akun.');
        } else {
            return back()->with('error', 'Gagal register: ' . $response->body());
        }
    }

    public function logout(Request $request)
    {
        Session::forget('supabase_token');
        return redirect()->route('login');
    }

    /**
     * Handle email confirmation from Supabase
     */
    public function confirmEmail(Request $request)
    {
        // Get tokens from URL parameters (hash fragments will be handled by JavaScript)
        $accessToken = $request->get('access_token');
        $refreshToken = $request->get('refresh_token');
        $tokenType = $request->get('token_type');
        $type = $request->get('type');
        $error = $request->get('error');
        $errorDescription = $request->get('error_description');

        // Handle errors
        if ($error) {
            return redirect()->route('login')->with('error', 'Konfirmasi email gagal: ' . ($errorDescription ?: $error));
        }

        // If we have tokens, process them
        if ($accessToken && $type === 'signup') {
            // Store the token in session
            Session::put('supabase_token', $accessToken);
            if ($refreshToken) {
                Session::put('supabase_refresh_token', $refreshToken);
            }
            
            return redirect()->route('dashboard')->with('success', 'Email berhasil dikonfirmasi! Selamat datang!');
        }

        // If no tokens in URL parameters, show confirmation page that will handle hash fragments
        return view('auth.confirm');
    }

    /**
     * Process email confirmation via AJAX
     */
    public function processEmailConfirmation(Request $request)
    {
        $accessToken = $request->input('access_token');
        $refreshToken = $request->input('refresh_token');
        $type = $request->input('type');

        if ($accessToken && $type === 'signup') {
            // Store the token in session
            Session::put('supabase_token', $accessToken);
            if ($refreshToken) {
                Session::put('supabase_refresh_token', $refreshToken);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Email berhasil dikonfirmasi!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Token konfirmasi tidak valid'
        ], 400);
    }

    /**
     * Handle general auth callback (fallback)
     */
    public function authCallback(Request $request)
    {
        // This can be used as a general callback endpoint
        // For now, redirect to confirm email handler
        return $this->confirmEmail($request);
    }
}
// This controller handles user authentication using Supabase.
// It includes methods for showing the login and registration forms, handling login and registration requests, and logging out users.
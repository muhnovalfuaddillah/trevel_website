<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($request->input('login'));
        $password = $request->input('password');

        // Clear session intended URL to prevent redirect loop back to login
        session()->forget('url.intended');

        // Check if input is email format (Owner) or Phone Number (Supir)
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $loginInput, 'password' => $password];
            if (Auth::attempt($credentials, $request->has('remember'))) {
                $request->session()->regenerate();
                $user = Auth::user();
                return redirect()->route('dashboard')
                    ->with('success', 'Selamat Datang Kembali, ' . $user->name . ' (OWNER ACCESS)!');
            }

            return back()->withErrors([
                'login' => 'Email Owner atau kata sandi yang Anda masukkan tidak cocok.',
            ])->onlyInput('login');
        } else {
            // Clean phone number input
            $cleanPhone = preg_replace('/[^0-9]/', '', $loginInput);

            $user = User::where('no_hp', $loginInput)
                ->orWhere('no_hp', $cleanPhone)
                ->orWhereHas('driver', function ($q) use ($loginInput, $cleanPhone) {
                    $q->where('nomor_hp', $loginInput)->orWhere('nomor_hp', $cleanPhone);
                })->first();

            if ($user && Hash::check($password, $user->password)) {
                Auth::login($user, $request->has('remember'));
                $request->session()->regenerate();
                return redirect()->route('dashboard')
                    ->with('success', 'Selamat Datang Kembali, ' . $user->name . ' (SUPIR / DRIVER)!');
            }

            return back()->withErrors([
                'login' => 'Nomor HP Supir atau kata sandi salah. Jika kendala login atau lupa kata sandi, silakan hubungi Owner / Management Travel.',
            ])->onlyInput('login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem (Logout).');
    }
}

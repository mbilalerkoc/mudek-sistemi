<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // KULLANICI ROLÜNE GÖRE YÖNLENDİRME
            if (Auth::user()->role === 'super_admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            // Normal öğretmen ise user dashboard'a yönlendir
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Sağlanan bilgiler kayıtlarımızla eşleşmiyor.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
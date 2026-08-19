<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\Messages\UserMessages;

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
        ], [
            'email.required' => 'E-posta alanı zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'password.required' => 'Şifre alanı zorunludur.',
        ]);

        // Beni hatırla seçeneği kontrolü
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'super_admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('user.dashboard'));
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', UserMessages::LOGIN_FAILED->value);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function directReset(Request $request)
{
    $request->validate([
        'email' => ['required', 'email', 'exists:users,email'],
        'password' => ['required', 'min:8'],
    ], [
        'email.exists' => 'Bu e-posta adresine kayıtlı bir kullanıcı bulunamadı.',
        'password.min' => 'Yeni şifre en az 8 karakter olmalıdır.',
    ]);

    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    return back()->with('success', UserMessages::PASSWORD_UPDATED->value);
}
}
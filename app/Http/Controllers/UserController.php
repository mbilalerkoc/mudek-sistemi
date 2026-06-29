<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function dersler()
{
    // resources/views/user/dersler.blade.php dosyasını çağırır
    return view('user.dersler');
}
}
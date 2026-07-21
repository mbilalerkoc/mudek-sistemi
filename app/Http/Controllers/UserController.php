<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
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
    $courses = Course::all();
    
    return view('user.dersler', compact('courses'));
}
}
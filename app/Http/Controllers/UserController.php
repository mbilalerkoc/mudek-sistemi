<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CourseRepositoryInterface;

class UserController extends Controller
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository
    ) {}

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
        $courses = $this->courseRepository->getCoursesByTeacher(auth()->user());
        return view('user.dersler.index', compact('courses'));
    }
}
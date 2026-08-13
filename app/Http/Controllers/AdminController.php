<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function teachers()
    {
        $teachers = User::where('role', '!=', 'super_admin')->get();
        return view('admin.users', compact('teachers')); // users.blade.php kullanıyoruz
    }

    public function courses()
    {
        $courses = Course::with(['users', 'exams'])->get();
        $teachers = User::where('role', '!=', 'super_admin')->get();
        return view('admin.courses.index', compact('courses', 'teachers'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:courses,code',
        ]);

        Course::create([
            'name' => $request->name,
            'code' => $request->code,
            'credit' => $request->credit ?? 3,
        ]);

        return redirect()->back()->with('success', 'Ders başarıyla eklendi!');
    }

    public function assignTeacher(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $course = Course::find($request->course_id);
        $course->users()->syncWithoutDetaching([$request->user_id]);

        return redirect()->back()->with('success', 'Öğretmen derse atandı!');
    }
}
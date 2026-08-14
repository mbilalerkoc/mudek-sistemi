<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Services\CourseService;

class AdminController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private CourseRepositoryInterface $courseRepository,
        private CourseService $courseService
    ) {}

    public function index()
    {
        return view('admin.dashboard');
    }

    public function teachers()
    {
        $teachers = $this->userRepository->getByRole('teacher');
        return view('admin.users', compact('teachers'));
    }

    public function courses()
    {
        $courses = $this->courseRepository->all();
        $teachers = $this->userRepository->getByRole('teacher');
        return view('admin.courses.index', compact('courses', 'teachers'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:courses,code',
        ]);

        $this->courseRepository->create([
            'name'    => $request->name,
            'code'    => $request->code,
            'credits' => $request->credits ?? 3,
        ]);

        return redirect()->back()->with('success', 'Ders başarıyla eklendi!');
    }

    public function assignTeacher(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id'   => 'required|exists:users,id',
        ]);

        $this->courseService->assignTeacher(
            $request->course_id,
            $request->user_id
        );

        return redirect()->back()->with('success', 'Öğretmen derse atandı!');
    }
}
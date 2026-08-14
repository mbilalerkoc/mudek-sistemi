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
        $courses = $this->courseRepository->allWithUsers();
        $teachers = $this->userRepository->getByRole('teacher');
        return view('admin.courses.index', compact('courses', 'teachers'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|unique:courses,code',
            'credits' => 'nullable|integer|min:1|max:10',
            'semester'=> 'nullable|string|max:50',
        ]);

        $this->courseRepository->create([
            'name'     => $request->name,
            'code'     => $request->code,
            'credits'  => $request->credits ?? 3,
            'semester' => $request->semester,
        ]);

        return redirect()->back()->with('success', 'Ders başarıyla eklendi!');
    }

    public function editCourse($id)
    {
        $course = $this->courseRepository->find($id);
        $teachers = $this->userRepository->getByRole('teacher');
        return view('admin.courses.edit', compact('course', 'teachers'));
    }

    public function updateCourse(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|unique:courses,code,' . $id,
            'credits' => 'nullable|integer|min:1|max:10',
            'semester'=> 'nullable|string|max:50',
        ]);

        $this->courseRepository->update($id, [
            'name'     => $request->name,
            'code'     => $request->code,
            'credits'  => $request->credits,
            'semester' => $request->semester,
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Ders başarıyla güncellendi!');
    }

    public function deleteCourse($id)
    {
        $this->courseRepository->delete($id);

        return redirect()->route('admin.courses.index')->with('success', 'Ders başarıyla silindi!');
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

    public function removeTeacher(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id'   => 'required|exists:users,id',
        ]);

        $this->courseService->removeTeacher(
            $request->course_id,
            $request->user_id
        );

        return redirect()->back()->with('success', 'Öğretmen dersten çıkarıldı!');
    }
}
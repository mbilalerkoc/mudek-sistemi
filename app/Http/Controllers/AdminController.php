<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\AcademicTitleRepositoryInterface;
use App\Services\CourseService;
use App\Services\UserService;
use App\Repositories\Interfaces\StudentCourseRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;

class AdminController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private CourseRepositoryInterface $courseRepository,
        private AcademicTitleRepositoryInterface $academicTitleRepository,
        private CourseService $courseService,
        private UserService $userService,
        private StudentCourseRepositoryInterface $studentCourseRepository,
        private StudentRepositoryInterface $studentRepository
    ) {}

    // --- DASHBOARD ---
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // ==========================================
    // USER MANAGEMENT (KULLANICI YÖNETİMİ)
    // ==========================================

    public function userIndex()
    {
        $users = $this->userRepository->getAllWithTitles();
        return view('admin.users.index', compact('users'));
    }

    public function userCreate()
    {
        $academicTitles = $this->academicTitleRepository->all();
        return view('admin.users.ekle', compact('academicTitles'));
    }

    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'surname'           => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:8',
            'role'              => 'required|in:super_admin,user,student',
            'academic_title_id' => 'nullable|exists:academic_titles,id',
        ]);

        $this->userService->createUser($validated);

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı başarıyla eklendi!');
    }

    public function userEdit($id)
    {
        $user = $this->userRepository->find($id);
        $academicTitles = $this->academicTitleRepository->all();
        
        return view('admin.users.edit', compact('user', 'academicTitles'));
    }

    public function userUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'surname'           => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $id,
            'password'          => 'nullable|min:8',
            'role'              => 'required|in:super_admin,user',
            'academic_title_id' => 'nullable|exists:academic_titles,id',
        ]);

        $this->userService->updateUser($id, $validated);

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı başarıyla güncellendi!');
    }

    public function userDestroy($id)
{
    $user = $this->userRepository->find($id);

    activity()
        ->performedOn($user)
        ->withProperties(['name' => $user->name, 'email' => $user->email])
        ->log('Kullanıcı silindi');

    $this->userRepository->delete($id);

    return redirect()->route('admin.users.index')->with('success', 'Kullanıcı başarıyla silindi!');
}

    // ==========================================
    // COURSE MANAGEMENT (DERS YÖNETİMİ)
    // ==========================================

        public function courseIndex()
        {
            $courses = $this->courseRepository->allWithUsers();
            // NOT: Blade dosyasında $teachers değişkenini kullandığımız için adını tekrar teachers yaptık
            $users = $this->userRepository->getByRole('user'); 
            
            return view('admin.courses.index', compact('courses', 'users'));
        }

    public function courseStore(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|unique:courses,code',
            'credits' => 'nullable|integer|min:1|max:10',
            'semester'=> 'nullable|string|max:50',
        ]);

        $this->courseService->createCourse($validated);

        return redirect()->back()->with('success', 'Ders başarıyla eklendi!');
    }

    public function courseEdit($id)
    {
        $course = $this->courseRepository->find($id);
        $users = $this->userRepository->getByRole('user');
        
        return view('admin.courses.edit', compact('course', 'users'));
    }

    public function courseUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|unique:courses,code,' . $id,
            'credits' => 'nullable|integer|min:1|max:10',
            'semester'=> 'nullable|string|max:50',
        ]);

        $this->courseService->updateCourse($id, $validated);

        return redirect()->route('admin.courses.index')->with('success', 'Ders başarıyla güncellendi!');
    }

    public function courseDestroy($id)
{
    $course = $this->courseRepository->find($id);

    activity()
        ->performedOn($course)
        ->withProperties(['code' => $course->code, 'name' => $course->name])
        ->log('Ders silindi');

    $this->courseRepository->delete($id);

    return redirect()->route('admin.courses.index')->with('success', 'Ders başarıyla silindi!');
}

    // ==========================================
    // COURSE - TEACHER ASSIGNMENTS (DERS ATAMALARI)
    // ==========================================

    public function assignTeacher(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id'   => 'required|exists:users,id',
        ]);

        $this->courseService->assignTeacher($validated['course_id'], $validated['user_id']);

        return redirect()->back()->with('success', 'Öğretmen derse atandı!');
    }

    public function removeTeacher(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id'   => 'required|exists:users,id',
        ]);

        $this->courseService->removeTeacher($validated['course_id'], $validated['user_id']);

        return redirect()->back()->with('success', 'Öğretmen dersten çıkarıldı!');
    }

    public function dersOgrencileri($id)
    {
        $course = $this->courseRepository->find($id);
        $kayitliOgrenciler = $this->studentCourseRepository->getByCourse($id);
        $kayitliIds = $kayitliOgrenciler->pluck('student_id');
        $tumOgrenciler = $this->studentRepository->all();
        $kayitsizOgrenciler = $tumOgrenciler->whereNotIn('id', $kayitliIds);

        return view('admin.courses.ogrenciler', compact('course', 'kayitliOgrenciler', 'kayitsizOgrenciler'));
    }

public function dersOgrenciEkle(Request $request, $id)
{
    $request->validate([
        'student_ids'   => 'required|array',
        'student_ids.*' => 'exists:students,id',
    ]);

    foreach ($request->student_ids as $studentId) {
        $this->courseService->enrollStudent($id, $studentId);
    }

    return redirect()->back()->with('success', count($request->student_ids) . ' öğrenci derse eklendi!');
}

public function dersOgrenciCikar($id, $student_id)
{
    $this->courseService->unenrollStudent($id, $student_id);

    return redirect()->back()->with('success', 'Öğrenci dersten başarıyla çıkarıldı!');
}

public function dersOgrenciCikarToplu(Request $request, $id)
{
    $request->validate([
        'student_ids'   => 'required|array',
        'student_ids.*' => 'exists:students,id',
    ]);

    foreach ($request->student_ids as $studentId) {
        $this->courseService->unenrollStudent($id, $studentId);
    }

    return redirect()->back()->with('success', count($request->student_ids) . ' öğrenci dersten çıkarıldı!');
}
}
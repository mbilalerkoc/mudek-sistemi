<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Services\GradeService;
use App\Enums\Messages\ExamMessages;
use App\Enums\Messages\FormMessages;

class DersController extends Controller
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository,
        private GradeService $gradeService
    ) {}

    // User paneli - sadece kendi dersleri
    public function index()
    {
        $courses = $this->courseRepository->getCoursesByTeacher(auth()->user());
        return view('user.dersler.index', compact('courses'));
    }

    // Admin paneli - tüm dersler
    // Admin paneli - tüm dersler (istatistikleri ve öğretmen bilgisiyle)
    public function adminIndex()
    {
        $courses = $this->courseRepository->allWithUsers();
        return view('user.dersler.index', compact('courses'));
    }

    // Her iki panel için aynı detay sayfası
    public function dersDetay($id)
    {
        $course = $this->courseRepository->find($id);
        return view('user.dersler.detay', compact('course'));
    }

    public function formGoster($ders_id, $form_id)
{
    $validForms = [1, 2, 3, 4];

    if (!in_array((int) $form_id, $validForms)) {
        return redirect()->back()->with('warning', 'Form bulunamadı.');
    }

    $data = $this->courseRepository->getCourseDetailsForForm($ders_id);

    $course   = $data['course'];
    $exams    = $data['exams'];
    $students = $data['students'];

    $isAdmin        = auth()->user()->role === 'super_admin';
    $dashboardRoute = $isAdmin ? 'admin.dashboard' : 'user.dashboard';
    $derslerRoute   = $isAdmin ? 'admin.dersler'   : 'user.dersler';
    $detayRoute     = $isAdmin ? 'admin.ders.detay' : 'user.ders.detay';

    return view('user.dersler.forms.index', compact(
        'course', 'form_id', 'exams', 'students',
        'isAdmin', 'dashboardRoute', 'derslerRoute', 'detayRoute'
    ));
}

    public function notlariDuzenle($id)
    {
        $data = $this->courseRepository->getCourseDetailsForForm($id);

        $course   = $data['course'];
        $exams    = $data['exams'];
        $students = $data['students'];

        return view('user.dersler.forms.not-guncelle', compact('course', 'exams', 'students'));
    }

    public function notlariKaydet(Request $request)
    {
        $this->gradeService->kaydet($request->all());

        // Admin mi user mı ona göre yönlendir
        $routeName = auth()->user()->role === 'super_admin'
            ? 'admin.ders.detay'
            : 'user.ders.detay';

        return redirect()
            ->route($routeName, $request->input('course_id'))
            ->with('success', ExamMessages::GRADES_SAVED->value);
    }

    public function katkilariniKaydet(Request $request)
    {
        $request->validate([
            'katkilar'   => 'required|array',
            'katkilar.*' => 'nullable|integer|min:1|max:5',
        ]);

        $routeName = auth()->user()->role === 'super_admin'
            ? 'admin.dersler'
            : 'user.dersler';

        return redirect()
            ->route($routeName)
            ->with('success', FormMessages::FORM_SAVED->value);
    }
}
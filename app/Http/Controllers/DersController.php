<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Services\GradeService;

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

    // Her iki panel için aynı form sayfası
    public function formGoster($ders_id, $form_id)
    {
        $data = $this->courseRepository->getCourseDetailsForForm($ders_id);

        $course   = $data['course'];
        $exams    = $data['exams'];
        $students = $data['students'];

        return view('user.dersler.forms.index', compact('course', 'form_id', 'exams', 'students'));
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
            ->with('success', 'Notlar başarıyla kaydedildi!');
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
            ->with('success', 'Form başarıyla kaydedildi!');
    }
}
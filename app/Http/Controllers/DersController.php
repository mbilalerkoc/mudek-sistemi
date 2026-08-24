<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Services\GradeService;
use App\Services\CourseService;
use App\Enums\Messages\ExamMessages;
use App\Enums\Messages\FormMessages;
use App\Repositories\Interfaces\AssignmentRepositoryInterface;

class DersController extends Controller
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository,
        private GradeService $gradeService,
        private AssignmentRepositoryInterface $assignmentRepository,
        private CourseService $courseService
    ) {}

    // ==========================================
    // DERS LİSTELEME
    // ==========================================
    public function index()
    {
        $courses = $this->courseService->getAllCoursesWithCompletionStatus(auth()->user());
        return view('dersler.index', compact('courses'));
    }

    // Admin paneli - tüm dersler
    public function adminIndex()
    {
        $courses = $this->courseService->getAllCoursesWithCompletionStatus(auth()->user());
        return view('dersler.index', compact('courses'));
    }

    // ==========================================
    // DERS DETAY
    // ==========================================

    public function dersDetay($id)
    {
        $course = $this->courseRepository->find($id);
        return view('dersler.detay', compact('course'));
    }


    public function formGoster($ders_id, $form_id)
    {
        $validForms = [1, 2, 3, 4];

        if (!in_array((int) $form_id, $validForms)) {
            return redirect()->back()->with('warning', 'Form bulunamadı.');
        }

        $data = $this->courseRepository->getCourseDetailsForForm($ders_id);

        $course      = $data['course'];
        $exams       = $data['exams'];
        $students    = $data['students'];
        $assignments = $this->assignmentRepository->getByCourse($ders_id);

        $isAdmin        = auth()->user()->role === 'super_admin';
        $dashboardRoute = $isAdmin ? 'admin.dashboard'  : 'user.dashboard';
        $derslerRoute   = $isAdmin ? 'admin.dersler'    : 'user.dersler';
        $detayRoute     = $isAdmin ? 'admin.ders.detay' : 'user.ders.detay';

        return view('dersler.forms.index', compact(
            'course', 'form_id', 'exams', 'students',
            'assignments', 'isAdmin', 'dashboardRoute',
            'derslerRoute', 'detayRoute'
        ));
    }

    // ==========================================
    // NOT GÜNCELLEME SAYFASI
    // ==========================================

    public function notlariDuzenle($id)
    {
        $data = $this->courseRepository->getCourseDetailsForForm($id);

        $course   = $data['course'];
        $exams    = $data['exams'];
        $students = $data['students'];

        $isAdmin        = auth()->user()->role === 'super_admin';
        $dashboardRoute = $isAdmin ? 'admin.dashboard'  : 'user.dashboard';
        $derslerRoute   = $isAdmin ? 'admin.dersler'    : 'user.dersler';
        $detayRoute     = $isAdmin ? 'admin.ders.detay' : 'user.ders.detay';

        return view('dersler.forms.notlar.not-guncelle', compact(
            'course', 'exams', 'students',
            'isAdmin', 'dashboardRoute', 'derslerRoute', 'detayRoute'
        ));
    }

    // ==========================================
    // NOT KAYDETME
    // ==========================================

    public function notlariKaydet(Request $request)
    {

        $this->gradeService->kaydet($request->all());

        $courseId = $request->input('course_id');
        $isAdmin = auth()->user()->role === 'super_admin';
        
        $routeName = $isAdmin ? 'admin.form.goster' : 'user.form.goster';

        return redirect()
            ->route($routeName, ['ders_id' => $courseId, 'form_id' => 1])
            ->with('success', ExamMessages::GRADES_SAVED->value);
    }

}
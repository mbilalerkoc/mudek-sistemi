<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Services\GradeService;

class DersController extends Controller
{
    protected $courseRepository;
    protected $gradeService;

    public function __construct(
        CourseRepositoryInterface $courseRepository,
        GradeService $gradeService
    ) {
        $this->courseRepository = $courseRepository;
        $this->gradeService = $gradeService;
    }

    public function index()
    {
        $courses = $this->courseRepository->getCoursesByTeacher(auth()->user());
        return view('user.dersler.index', compact('courses'));
    }

    /**
     * Rotalarda /user/dersler/{id} ile çağrılan ana ders detay / notlar metodu
     */
    public function dersDetay($id, $form_id = 1)
    {
        $data = $this->courseRepository->getCourseDetailsForForm($id);
        
        $course = $data['course'];
        $exams = $data['exams'];
        $students = $data['students'];

        return view('user.dersler.forms.index', compact('course', 'form_id', 'exams', 'students'));
    }

    public function formGoster($ders_id, $form_id)
    {
        $data = $this->courseRepository->getCourseDetailsForForm($ders_id);
        
        $course = $data['course'];
        $exams = $data['exams'];
        $students = $data['students'];

        return view('user.dersler.forms.index', compact('course', 'form_id', 'exams', 'students'));
    }

    public function notlariDuzenle($id)
{
    $data = $this->courseRepository->getCourseDetailsForForm($id);

    $course = $data['course'];
    $exams = $data['exams'];
    $students = $data['students'];

    return view('user.dersler.forms.not-guncelle', compact('course', 'exams', 'students'));
}

    public function notlariKaydet(Request $request)
    {
        $this->gradeService->kaydet($request->all());

        return redirect()
            ->route('user.ders.detay', $request->input('course_id'))
            ->with('success', 'Notlar başarıyla kaydedildi ve ortalamalar hesaplandı!');
    }
}
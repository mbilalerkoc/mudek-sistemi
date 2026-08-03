<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Models\StudentExam;

class DersController extends Controller
{
    protected $courseRepository;

    // Dependency Injection ile Repository'yi buraya çağırıyoruz
    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function index()
    {
        // Veritabanı sorgusu yerine repository metodunu kullanıyoruz
        $courses = $this->courseRepository->getCoursesByTeacher(auth()->user());

        return view('user.dersler.index', compact('courses'));
    }
    public function dersDetay($id)
    {
        // Dersin detay bilgilerini repository üzerinden alıyoruz
        $course = $this->courseRepository->find($id);

        return view('user.dersler.detay', compact('course'));
    }
    public function formGoster($ders_id, $form_id)
    {
        // Karmaşık tüm veriler artık repository içinden tek satırda geliyor
        $data = $this->courseRepository->getCourseDetailsForForm($ders_id);
        
        $course = $data['course'];
        $exams = $data['exams'];
        $students = $data['students'];

        return view('user.dersler.forms.index', compact('course', 'form_id', 'exams', 'students'));
    }

    public function notlariKaydet(Request $request)
    {
        // Not kaydetme işlemleri
        if ($request->has('grades')) {
            foreach ($request->grades as $studentExamId => $notlar) {
                $studentExam = StudentExam::find($studentExamId);
                if ($studentExam) {
                    $examScore = $notlar['exam_score'] ?? 0;
                    $assignmentScore = $studentExam->assignment_score ?? 0;
                    
                    $studentExam->update([
                        'exam_score' => $examScore,
                        'total_score' => $examScore + $assignmentScore
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Notlar başarıyla kaydedildi!');
    }
}
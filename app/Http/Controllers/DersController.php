<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\StudentCourse; // student_courses tablosu için
use App\Models\StudentExam; // student_exams tablosu için
use App\Models\Student; // Student modelini ekliyoruz

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
    $course = Course::findOrFail($ders_id);
    
    // 1. Bu derse ait sınavları çekiyoruz
    $exams = $course->exams ?? collect(); 

    // 2. Öğrencileri, ara tablo (studentCourses) ve bu kayda ait sınav sonuçlarıyla (studentExams) çekiyoruz
    $students = $course->students()->with([
        'studentCourses' => function($query) use ($ders_id) {
            $query->where('course_id', $ders_id);
        },
        'studentExams' => function($query) use ($exams) {
            $examIds = $exams->isNotEmpty() ? $exams->pluck('id')->toArray() : [];
            $query->whereIn('exam_id', $examIds);
        }
    ])->get();

    // 3. Blade dosyasının doğrudan beklediği $students değişkenini yolluyoruz
    return view('user.dersler.forms.index', compact('course', 'form_id', 'exams', 'students'));
}
    public function notlariKaydet(Request $request)
    {
        $request->validate([
            'grades' => 'required|array'
        ]);

        foreach ($request->grades as $studentExamId => $score) {
            $studentExam = StudentExam::find($studentExamId);

            if($studentExam) {
                $studentExam->update([
                    'exam_score' => $score,
                    'total_score' => $score + ($studentExam->assignment_score ?? 0) // Ödev puanı varsa ekle
                ]);
            }
        }
        return redirect()->back()->with('success', 'Notlar başarıyla kaydedildi!');
    }
}
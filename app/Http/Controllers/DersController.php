<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\StudentCourse; // student_courses tablosu için
use App\Models\StudentExam; // student_exams tablosu için
use App\Models\Student; // Student modelini ekliyoruz

class DersController extends Controller
{
    public function index()
    {
        // Artık dersleri doğrudan user_id ile değil, user_courses köprü tablosu (ilişkisi) üzerinden çekiyoruz
        $courses = auth()->user()->courses; 

        return view('user.dersler.index', compact('courses'));
    }

    public function dersDetay($id)
    {
        $course = Course::findOrFail($id);

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

    public function katkilariniKaydet(Request $request)
    {
        $request->validate([
            'katkilar'   => 'required|array',
            'katkilar.*' => 'nullable|integer|min:1|max:5',
        ], [
            'katkilar.*.min' => 'Katkı puanı en az 1 olmalıdır.',
            'katkilar.*.max' => 'Katkı puanı en fazla 5 olmalıdır.'
        ]);

        return redirect()->back()->with('success', 'Form başarıyla kaydedildi!');
    }
}
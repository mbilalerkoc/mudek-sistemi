<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\StudentCourse; // student_courses tablosu için

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
        
        // Öğrencileri ve bu derse ait ara tablo (student_courses) verilerini birlikte çekiyoruz
        $students = $course->students()->with(['studentCourses' => function($query) use ($ders_id) {
            $query->where('course_id', $ders_id);
        }])->get();

        return view('user.dersler.forms.index', compact('course', 'form_id', 'students'));
    } 

    public function notlariKaydet(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'grades' => 'required|array'
        ]);

        $courseId = $request->input('course_id');

        foreach ($request->grades as $student_id => $notlar) {
            // Notlar artık student_courses tablosunda tutuluyor
            StudentCourse::updateOrCreate(
                [
                    'student_id' => $student_id,
                    'course_id' => $courseId
                ],
                [
                    'midterm' => $notlar['midterm'] ?? null,
                    'final'   => $notlar['final'] ?? null,
                    'makeup'  => $notlar['makeup'] ?? null,
                ]
            );
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
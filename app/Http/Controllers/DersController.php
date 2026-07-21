<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Grade;

class DersController extends Controller
{
    public function index()
    {
        $courses = Course::where('user_id', auth()->user()->id)->get();

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
        $students = $course->students;

        return view('user.dersler.forms.index', compact('course', 'form_id', 'students'));
    }   

    public function notlariKaydet(Request $request)
    {
        foreach ($request->grades as $student_id => $notlar) {
            Grade::updateOrCreate(
                ['student_id' => $student_id],
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
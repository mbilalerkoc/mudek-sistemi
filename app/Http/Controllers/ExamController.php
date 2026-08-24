<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExamService;
use App\Enums\Messages\ExamMessages;
use App\Models\Exam;
use Exception;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    public function __construct(
        protected ExamService $examService
    ) {}

    // SINAV ANA SAYFASI (sinav-kagitlari)

    public function index($dersId)
    {
        $isAdmin = auth()->user()->role === 'super_admin';
        $course  = $this->examService->getCourseExams($dersId);

        return view('user.dersler.forms.sinav-kagitlari', compact('course', 'isAdmin'));
    }

    // SINAV BİLGİLERİ GÜNCELLEME (tarih, kağıtlar)

    public function updateExam(Request $request, $examId)
{
    $request->validate([
        'exam_date'           => 'nullable|date',
        'weight'              => 'required|integer|min:0|max:100',
        'grading_type'        => 'required|in:weighted,raw_sum',
        'question_paper_path' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        'answers_paper_path'  => 'nullable|file|mimes:pdf,doc,docx|max:10240',
    ]);

    try {
        $result = $this->examService->updateExam($request->all(), $examId);
        return redirect()->back()->with('success', $result['message']);
    } catch (Exception $e) {
        Log::error('Sınav güncelleme hatası: ' . $e->getMessage());
        return redirect()->back()->with('error', ExamMessages::EXAM_UPDATE_FAILED->value); // ← değişti
    }
}

    // SORU SAYFASI

    public function studentExamDetails($examId)
    {
        $isAdmin = auth()->user()->role === 'super_admin';
        $exam    = $this->examService->getExamDetails($examId);

        return view('dersler.forms.sinavlar.student_exams', compact('exam', 'isAdmin'));
    }

    public function storeQuestion(Request $request, $examId)
{
    $request->validate([
        'score' => 'required|numeric|min:0',
        'file'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    try {
        $result = $this->examService->storeQuestion($request->all(), $examId);
        return redirect()->back()->with('success', $result['message']);
    } catch (Exception $e) {
        Log::error('Soru ekleme hatası: ' . $e->getMessage());
        return redirect()->back()->with('error', ExamMessages::QUESTION_STORE_FAILED->value); // ← değişti
    }
}
    public function deleteQuestion($questionId)
{
    try {
        $this->examService->deleteQuestion($questionId);
        return redirect()->back()->with('success', ExamMessages::QUESTION_DELETED->value);
    } catch (\Exception $e) {
        Log::error('Soru silme hatası: ' . $e->getMessage());
        return redirect()->back()->with('error', ExamMessages::ERROR_OCCURRED->value);
    }
}

    // CEVAP SAYFASI (Salt Okunur)

    public function cevaplarGoster($examId)
    {
        $isAdmin = auth()->user()->role === 'super_admin';
        $exam    = Exam::with([
                        'questions',
                        'studentExams.studentCourse.student',
                        'studentExams.answers',
                    ])->findOrFail($examId);

        return view('dersler.forms.sinavlar.sinav-cevaplar', compact('exam', 'isAdmin'));
    }

    // CEVAP DÜZENLEME SAYFASI

    public function cevaplarEdit($examId)
    {
        $isAdmin = auth()->user()->role === 'super_admin';
        $exam    = Exam::with([
                        'questions',
                        'studentExams.studentCourse.student',
                        'studentExams.answers',
                    ])->findOrFail($examId);

        return view('dersler.forms.sinavlar.cevaplar-duzenle', compact('exam', 'isAdmin'));
    }

    // CEVAPLARI KAYDET (Manuel giriş)

    public function cevaplariKaydet(Request $request, $examId)
{
    try {
        $this->examService->cevaplariKaydet($request->all());

        $isAdmin     = auth()->user()->role === 'super_admin';
        $routePrefix = $isAdmin ? 'admin.sinavlar.cevaplar' : 'user.sinavlar.cevaplar';

        return redirect()->route($routePrefix, $examId)
                         ->with('success', ExamMessages::GRADES_SAVED->value);
    } catch (Exception $e) {
        Log::error('Cevap kaydetme hatası: ' . $e->getMessage());
        return redirect()->back()->with('error', ExamMessages::GRADES_SAVE_FAILED->value); 
    }
}


// EXCEL'DEN İÇE AKTARMA

    public function importExcel(Request $request, $examId)
{
    $request->validate([
        'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
    ]);

    try {
        $this->examService->importAnswersFromExcel($request->file('excel_file'), $examId);

        $isAdmin     = auth()->user()->role === 'super_admin';
        $routePrefix = $isAdmin ? 'admin.sinavlar.cevaplar' : 'user.sinavlar.cevaplar';

        return redirect()->route($routePrefix, $examId)
                         ->with('success', ExamMessages::EXCEL_IMPORTED->value); 
    } catch (Exception $e) {
        Log::error('Excel import hatası: ' . $e->getMessage());
        return redirect()->back()->with('error', ExamMessages::EXCEL_IMPORT_FAILED->value); 
    }
}


    // GENEL NOT KAYDETME (sinav-kagitlari sayfasından)

    public function saveStudentGrades(Request $request, $examId)
{
    try {
        $this->examService->saveStudentGrades($request->all(), $examId);
        return redirect()->back()->with('success', ExamMessages::GRADES_SAVED->value);
    } catch (Exception $e) {
        Log::error('Not kaydetme hatası: ' . $e->getMessage());
        return redirect()->back()->with('error', ExamMessages::GRADES_SAVE_FAILED->value); 
    }
}

    public function saveSampleExamPapers(Request $request, $examId)
{
    $routeName = auth()->user()->role === 'super_admin'
        ? 'admin.sinavlar.cevaplar'
        : 'user.sinavlar.cevaplar';

    try {
        $data = $request->all();

        foreach (['best', 'average', 'worst'] as $type) {
            if ($request->hasFile("{$type}.file")) {
                $data[$type]['file'] = $request->file("{$type}.file");
            }
        }

        $this->examService->saveSampleExamPapers($data, $examId);

        return redirect()
            ->route($routeName, $examId)
            ->with('success', ExamMessages::SAMPLE_PAPERS_SAVED->value); 
    } catch (\Exception $e) {
        Log::error('Örnek kağıt kaydetme hatası: ' . $e->getMessage());

        return redirect()
            ->route($routeName, $examId)
            ->with('error', ExamMessages::SAMPLE_PAPERS_FAILED->value); 
    }
}
}
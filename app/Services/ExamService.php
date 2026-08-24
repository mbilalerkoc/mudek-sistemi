<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Answer;
use App\Models\StudentExam;
use App\Models\AssignmentSubmission;
use App\Repositories\Interfaces\ExamRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\StudentExamRepositoryInterface;
use App\Enums\Messages\ExamMessages;
use Illuminate\Support\Facades\Log;

class ExamService
{
    public function __construct(
        private ExamRepositoryInterface $examRepository,
        private CourseRepositoryInterface $courseRepository,
        private StudentExamRepositoryInterface $studentExamRepository,
        private GradeService $gradeService
    ) {}

    // SINAV YÖNETİMİ

    public function getCourseExams($dersId)
    {
        return $this->courseRepository->find($dersId);
    }

    public function getExamDetails($examId)
    {
        return $this->examRepository->findExamWithDetails($examId);
    }

    public function updateExam(array $data, $examId)
    {
        if (isset($data['question_paper_path']) && $data['question_paper_path'] instanceof \Illuminate\Http\UploadedFile) {
            $data['question_paper_path'] = $data['question_paper_path']->store('exams/questions', 'public');
        }

        if (isset($data['answers_paper_path']) && $data['answers_paper_path'] instanceof \Illuminate\Http\UploadedFile) {
            $data['answers_paper_path'] = $data['answers_paper_path']->store('exams/answers', 'public');
        }

        // --- HAM PUAN TOPLAMA KONTROLÜ ---
        if (isset($data['grading_type']) && $data['grading_type'] === 'raw_sum') {
            $exam = $this->examRepository->findExamWithDetails($examId);
            $examWeight = (int) ($data['weight'] ?? $exam->weight ?? 80);
            
            $totalAssignmentMaxScore = 0;
            if ($exam && $exam->examAssignments) {
                foreach ($exam->examAssignments as $ea) {
                    $totalAssignmentMaxScore += $ea->assignment->max_score ?? 0;
                }
            }

            $expectedAssignmentScore = 100 - $examWeight;

            // Eğer ödev varsa ve toplamları 100'ü bulmuyorsa Enum üzerinden uyarı fırlat
            if ($totalAssignmentMaxScore > 0 && $totalAssignmentMaxScore !== $expectedAssignmentScore) {
                session()->flash('raw_sum_warning', ExamMessages::RAW_SUM_WARNING->value);
            }
        }


        $this->examRepository->update($examId, $data);

        activity('sinav')
            ->performedOn($this->examRepository->find($examId))
            ->withProperties(['exam_id' => $examId])
            ->log(ExamMessages::UPDATED->value);

        return ['status' => true, 'message' => ExamMessages::UPDATED->value];
    }

    // SORU YÖNETİMİ

    public function storeQuestion(array $data, $examId)
    {
        $data['exam_id'] = $examId;

        if (isset($data['file']) && $data['file'] instanceof \Illuminate\Http\UploadedFile) {
            $data['file'] = $data['file']->store('exams/question_files', 'public');
        }

        $question = $this->examRepository->createQuestion($data);

        activity('sinav_soru')
            ->withProperties(['exam_id' => $examId, 'score' => $data['score']])
            ->log(ExamMessages::QUESTION_ADDED->value);

        return ['status' => true, 'message' => ExamMessages::QUESTION_ADDED->value, 'question' => $question];
    }

    public function deleteQuestion($questionId)
    {
        $question = \App\Models\Question::findOrFail($questionId);
        $examId = $question->exam_id;
        
        // Varsa dosyayı diskten sil
        if ($question->file) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($question->file);
        }

        $question->delete();

        // Enum standartına uygun loglama
        activity('sinav_soru')
            ->withProperties(['exam_id' => $examId, 'question_id' => $questionId])
            ->log(ExamMessages::QUESTION_DELETED->value);

        return true;
    }

    // PUANLAMA - Manuel Cevap Girişi

    public function cevaplariKaydet(array $data): void
    {
        if (!isset($data['grades']) || !is_array($data['grades'])) {
            return;
        }

        foreach ($data['grades'] as $studentExamId => $entry) {
            $studentExam = StudentExam::find($studentExamId);
            if (!$studentExam) continue;

            // Seviye güncelle (iyi/orta/kötü)
            if (isset($entry['level'])) {
                $studentExam->level = $entry['level'];
                $studentExam->save();
            }

            // Cevapları kaydet
            if (isset($entry['answers']) && is_array($entry['answers'])) {
                foreach ($entry['answers'] as $questionId => $score) {
                    if ($score === null || $score === '') continue;

                    Answer::updateOrCreate(
                        [
                            'student_exam_id' => $studentExamId,
                            'question_id'     => $questionId,
                        ],
                        ['score' => $score]
                    );
                }
            }

            // Puanları yeniden hesapla
            $this->skorlariYenidenHesapla($studentExam);

            // Genel ders ortalamasını güncelle
            $this->gradeService->ortalamaHesaplaVeGuncelle($studentExam->student_course_id);
        }

        activity('sinav_not')
            ->withProperties(['grades_count' => count($data['grades'])])
            ->log(ExamMessages::GRADES_SAVED->value);
    }

    // PUANLAMA - Excel'den İçe Aktarma

    public function importAnswersFromExcel($file, $examId)
    {
        $exam = $this->examRepository->findExamWithDetails($examId);
        $courseId = $exam->course_id;
        $questions = $exam->questions->values(); 


        $arrayData = \Maatwebsite\Excel\Facades\Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\ToArray {
            public function array(array $array): void {} // Gerekli imza
        }, $file);

        $data = $arrayData[0] ?? [];

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            foreach ($data as $index => $row) {
                if ($index === 0) continue; 

                $studentNo = $row[0] ?? null; 

                if (empty($studentNo) || str_contains(strtolower($studentNo), 'öğrenci') || str_contains(strtolower($studentNo), 'no')) {
                    continue;
                }
                $student = \App\Models\Student::firstOrCreate(
                    ['student_no' => $studentNo],
                    ['name' => 'Bilinmeyen', 'surname' => 'Öğrenci']
                );

                $studentCourse = \App\Models\StudentCourse::firstOrCreate([
                    'student_id' => $student->id,
                    'course_id' => $courseId
                ]);

                $studentExam = \App\Models\StudentExam::firstOrCreate([
                    'student_course_id' => $studentCourse->id,
                    'exam_id' => $exam->id
                ]);

                // Soruları okuma ve puanlama
                foreach ($questions as $qIndex => $question) {
                    $excelColumnIndex = 7 + $qIndex; 
                    $isCorrect = isset($row[$excelColumnIndex]) ? (int)$row[$excelColumnIndex] : 0;
                    $earnedScore = $isCorrect === 1 ? $question->score : 0;

                    \App\Models\Answer::updateOrCreate(
                        [
                            'student_exam_id' => $studentExam->id, 
                            'question_id' => $question->id
                        ],
                        ['score' => $earnedScore]
                    );
                }

                $this->skorlariYenidenHesapla($studentExam);
            }

            \Illuminate\Support\Facades\DB::commit();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error("Excel import hatası: " . $e->getMessage());
            throw new \Exception("Excel içe aktarılırken bir hata oluştu. Lütfen dosya formatını kontrol edin.");
        }
    }

   public function saveStudentAnswers(array $gradesData, $examId)
    {
        DB::beginTransaction();

        try {
            foreach ($gradesData as $studentExamId => $data) {
                
                $studentExam = StudentExam::find($studentExamId);
                
                if (!$studentExam) {
                    continue; 
                }

                // Soru cevaplarını güncelle
                if (isset($data['answers']) && is_array($data['answers'])) {
                    foreach ($data['answers'] as $questionId => $score) {
                        $scoreValue = $score !== null && $score !== '' ? (float) $score : 0;

                        Answer::updateOrCreate(
                            [
                                'student_exam_id' => $studentExamId,
                                'question_id'     => $questionId,
                            ],
                            ['score' => $scoreValue]
                        );
                    }
                }

                // Seviyeyi güncelle (total_score hesaplaması ayrı metodda yapılacak)
                $studentExam->update([
                    'level' => $data['level'] ?? null
                ]);

                // YENİ EKLENEN KISIM: Soru puanları manuel değiştikten sonra total_score'u hesapla
                $this->skorlariYenidenHesapla($studentExam);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error("Cevap kaydetme hatası: " . $e->getMessage());
            throw new Exception("Öğrenci cevapları kaydedilirken bir hata oluştu.");
        }
    }

    public function skorlariYenidenHesapla(StudentExam $studentExam): void
    {
        // İlişkileri güvenli şekilde yükle
        $studentExam->loadMissing(['exam.examAssignments.assignment', 'studentCourse']);
        
        $studentId = $studentExam->studentCourse->student_id ?? null;
        $exam = $studentExam->exam ?? null;

        // Öğrenci veya sınav ilişkisi kopmuşsa (null ise) patlamaması için kontrol
        if (!$studentId || !$exam) {
            return;
        }

        $gradingType = $exam->grading_type ?? 'weighted';

        // 1. Ham Sınav Puanını Belirle (Sorular işaretlenmişse oradan, yoksa hocanın elle girdiği veriden al)
        $hasAnswers = $studentExam->answers()->exists();
        $rawPaperScore = $hasAnswers ? $studentExam->answers()->sum('score') : (float)($studentExam->exam_score ?? 0);

        // 2. Ham Ödev Puanları Toplamını Belirle
        $rawAssignmentScore = 0;
        if ($exam->examAssignments && $exam->examAssignments->count() > 0) {
            foreach ($exam->examAssignments as $examAssignment) {
                $submission = \App\Models\AssignmentSubmission::where('assignment_id', $examAssignment->assignment_id)
                    ->where('student_id', $studentId)
                    ->first();
                    
                if ($submission && $submission->grade_score !== null) {
                    $rawAssignmentScore += (float)$submission->grade_score;
                }
            }
        }

        if ($gradingType === 'raw_sum') { // ham puan toplama modunda, sınav ve ödev puanlarını direkt topla
            $rawTotalScore = $rawPaperScore + $rawAssignmentScore;
        } 
        else { // ağırlıklı puanlama modunda, sınav ve ödevlerin ağırlıklarını dikkate al
            $examWeight = (int) ($exam->weight ?? 80);
            $hasAssignments = $exam->examAssignments && $exam->examAssignments->count() > 0;

            if (!$hasAssignments) {
                $examWeight = 100;
            }
            $assignmentWeight = $hasAssignments ? (100 - $examWeight) : 0;
            $maxExamPossibleScore = $exam->questions()->sum('score') ?: 100;
            
            // Sınavın Katkısı
            $examScoreContributed = ($maxExamPossibleScore > 0) 
                ? ($rawPaperScore / $maxExamPossibleScore) * $examWeight 
                : 0;

            // Ödevlerin Katkısı
            $assignmentScoreContributed = 0;
            if ($hasAssignments) {
                foreach ($exam->examAssignments as $examAssignment) {
                    $submission = \App\Models\AssignmentSubmission::where('assignment_id', $examAssignment->assignment_id)
                        ->where('student_id', $studentId)
                        ->first();
                        
                    if ($submission && $submission->grade_score !== null) {
                        $maxAssignmentScore = $examAssignment->assignment->max_score ?? 100;
                        $assignmentScoreContributed += ($maxAssignmentScore > 0) 
                            ? ($submission->grade_score / $maxAssignmentScore) * $assignmentWeight 
                            : 0;
                    }
                }
            }

            // Ağırlıklı Toplam
            $rawTotalScore = $examScoreContributed + $assignmentScoreContributed;
        }

        // 3. Ortak Güvenlik Duvarı
        $totalScore = min(100, max(0, $rawTotalScore));

        // 4. Veritabanını Güncelle
        $studentExam->update([
            'exam_score'       => $rawPaperScore,
            'assignment_score' => $rawAssignmentScore,
            'total_score'      => round($totalScore, 2)
        ]);
    }

    public function saveSampleExamPapers(array $data, $examId)
{
    $types = ['best', 'average', 'worst'];

    foreach ($types as $type) {
        if (isset($data[$type]['student_exam_id']) && !empty($data[$type]['student_exam_id'])) {
            $selectedStudentExamId = $data[$type]['student_exam_id'];

            $targetStudentExam = \App\Models\StudentExam::find($selectedStudentExamId);
            if (!$targetStudentExam) {
                continue;
            }

            if (isset($data[$type]['file']) && $data[$type]['file'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $data[$type]['file'];

                // Eski dosya varsa sil
                if ($targetStudentExam->path && \Illuminate\Support\Facades\Storage::disk('public')->exists($targetStudentExam->path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($targetStudentExam->path);
                }

                // Orijinal dosya ismini koru, sadece tip bazlı alt klasörle çakışmayı önle
                $originalFilename = $file->getClientOriginalName();
                $path = $file->storeAs("exams/{$examId}/{$type}", $originalFilename, 'public');

                $targetStudentExam->path = $path;
                $targetStudentExam->save();
            }
        }
    }

    activity('sinav_ornek_kagit')
        ->withProperties(['exam_id' => $examId])
        ->log('Örnek sınav kağıtları güncellendi.');
}
}

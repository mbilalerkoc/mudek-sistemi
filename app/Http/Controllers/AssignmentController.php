<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AssignmentService;
use App\Enums\Messages\AssignmentMessages;

class AssignmentController extends Controller
{
    protected $assignmentService;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /*
    |--------------------------------------------------------------------------
    | ÖDEV OLUŞTUR
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, $courseId)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:191',
            'description' => 'nullable|string',
            'max_score'   => 'required|numeric|min:0|max:100',
            'due_date'    => 'required|date',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $validated['course_id'] = $courseId;

        /*
        |--------------------------------------------------------------------------
        | ÖDEV DOSYASI
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file')) {
            $validated['file_path'] = $this->assignmentService
            ->uploadAssignmentFile($request->file('file'));
        }

        unset($validated['file']);

        $this->assignmentService->createAssignment($validated);

        $routeName = auth()->user()->role === 'super_admin'
            ? 'admin.form.goster'
            : 'user.form.goster';

        return redirect()
            ->route($routeName, [
                'ders_id' => $courseId,
                'form_id' => 3
            ])
            ->with(
                'success',
                AssignmentMessages::CREATED->value
            );
    }

    /*
    |--------------------------------------------------------------------------
    | ÖDEV SİL
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $assignment = $this->assignmentService->findAssignment($id);

        $courseId = $assignment->course_id;

        $this->assignmentService->deleteAssignment($id);

        $routeName = auth()->user()->role === 'super_admin'
            ? 'admin.form.goster'
            : 'user.form.goster';

        return redirect()
            ->route($routeName, [
                'ders_id' => $courseId,
                'form_id' => 3
            ])
            ->with(
                'success',
                AssignmentMessages::DELETED->value
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TESLİMLERİ GÖSTER
    |--------------------------------------------------------------------------
    */

    public function teslimler($dersId, $odevId)
    {
        $assignment = $this->assignmentService
            ->findAssignment($odevId);

        $course = $assignment->course;

        $students = $course->students;

        $submissions = $this->assignmentService
            ->getSubmissions($odevId);

        /*
        |--------------------------------------------------------------------------
        | Öğrenci ID'sine göre teslimleri eşleştir
        |--------------------------------------------------------------------------
        */

        $submissionMap = $submissions->keyBy('student_id');

        $isAdmin = auth()->user()->role === 'super_admin';

        $formRoute = $isAdmin
            ? 'admin.form.goster'
            : 'user.form.goster';

        $saveRoute = $isAdmin
            ? 'admin.dersler.odevler.teslimler.kaydet'
            : 'user.dersler.odevler.teslimler.kaydet';

        return view(
            'user.dersler.forms.teslimler',
            compact(
                'assignment',
                'course',
                'students',
                'submissionMap',
                'formRoute',
                'saveRoute'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TESLİMLERİ KAYDET
    |--------------------------------------------------------------------------
    */

    public function teslimKaydet(Request $request, $dersId, $odevId)
    {
        $request->validate([
            'submissions'               => 'required|array',
            'submissions.*.grade_score' => 'nullable|numeric|min:0|max:100',
            'submissions.*.file'        => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'submissions.*.delete_file' => 'nullable|boolean',
        ]);

        foreach ($request->submissions as $studentId => $data) {

            /*
            |--------------------------------------------------------------------------
            | Mevcut submission
            |--------------------------------------------------------------------------
            */

            $submission = $this->assignmentService
                ->getSubmission(
                    $odevId,
                    $studentId
                );

            /*
            |--------------------------------------------------------------------------
            | Mevcut dosya yolu
            |--------------------------------------------------------------------------
            */

            $filePath = $submission
                ? $submission->file_path
                : null;

            /*
            |--------------------------------------------------------------------------
            | PUAN
            |--------------------------------------------------------------------------
            */

            $gradeScore = isset($data['grade_score'])
                && $data['grade_score'] !== ''
                ? $data['grade_score']
                : null;

            /*
            |--------------------------------------------------------------------------
            | DOSYA SİL
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['delete_file']) &&
                $data['delete_file'] == '1'
            ) {

                if ($filePath) {
                    $this->assignmentService
                        ->deleteSubmissionFile($filePath);
                }

                $filePath = null;
            }

            /*
            |--------------------------------------------------------------------------
            | YENİ DOSYA
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['file']) &&
                $data['file'] instanceof \Illuminate\Http\UploadedFile
            ) {

                /*
                 * Eski dosya varsa sil.
                 */
                if ($filePath) {
                    $this->assignmentService
                        ->deleteSubmissionFile($filePath);
                }

                /*
                 * Yeni dosyayı Service üzerinden yükle.
                 */
                $filePath = $this->assignmentService
                    ->uploadSubmissionFile(
                        $data['file'],
                        $odevId,
                        $studentId
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | SERVICE
            |--------------------------------------------------------------------------
            */

            $this->assignmentService->saveSubmission(
                $odevId,
                $studentId,
                [
                    'grade_score' => $gradeScore,
                    'file_path'   => $filePath,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        $isAdmin = auth()->user()->role === 'super_admin';

        $routeName = $isAdmin
            ? 'admin.form.goster'
            : 'user.form.goster';

        return redirect()
            ->route($routeName, [
                'ders_id' => $dersId,
                'form_id' => 3,
            ])
            ->with(
                'success',
                AssignmentMessages::SUBMISSION_SAVED->value
            );
    }
}
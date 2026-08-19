<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Services\StudentService;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Enums\Messages\StudentMessages;

class StudentController extends Controller
{
    public function __construct(
        private StudentRepositoryInterface $studentRepository,
        private StudentService $studentService
    ) {}

    public function index()
    {
        $students = $this->studentRepository->all();
        return view('admin.students.index', compact('students'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name'       => 'required|string|max:255',
        'surname'    => 'required|string|max:255',
        'student_no' => 'required|string|unique:students,student_no',
    ], [
        'student_no.unique' => 'Bu öğrenci numarası zaten kayıtlı.'
    ]);

    $this->studentService->createStudent($validated);

    return redirect()->back()->with('success', StudentMessages::CREATED->value);
}

    public function edit($id)
    {
        $student = $this->studentRepository->find($id);
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'surname'    => 'required|string|max:255',
            'student_no' => 'required|string|unique:students,student_no,' . $id,
        ]);

        $this->studentService->updateStudent($id, $validated);

        return redirect()->route('admin.students.index')->with('success', StudentMessages::UPDATED->value);
    }

    public function destroy($id)
    {
        $student = $this->studentRepository->find($id);

        activity()
            ->performedOn($student)
            ->withProperties(['name' => $student->name, 'student_no' => $student->student_no])
            ->log('Öğrenci silindi');

        $this->studentRepository->delete($id);

        return redirect()->route('admin.students.index')->with('success', StudentMessages::DELETED->value);
    }

    public function importExcel(Request $request)
{
    $request->validate([
        'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048',
    ], [
        'excel_file.required' => 'Lütfen bir dosya seçin.',
        'excel_file.mimes'    => 'Sadece .xlsx, .xls veya .csv dosyası yükleyebilirsiniz.',
        'excel_file.max'      => 'Dosya boyutu en fazla 2MB olabilir.',
    ]);

    $import = new StudentsImport;
    Excel::import($import, $request->file('excel_file'));

    $failures = $import->failures();
    $errors   = $import->errors();

    // Eğer validasyon hatası veya duplicate (unique) takılan satır varsa
    if ($failures->isNotEmpty() || count($errors) > 0) {
        $hatalar = [];

        foreach ($failures as $failure) {
            // Örn: "Satır 4: Bu öğrenci numarası zaten sistemde kayıtlı."
            $hatalar[] = "Satır {$failure->row()}: " . implode(', ', $failure->errors());
        }

        foreach ($errors as $error) {
            $hatalar[] = $error->getMessage();
        }

        return redirect()->back()
            ->with('import_errors', $hatalar);
    }

    $msg = $import->getImportedCount() . ' öğrenci başarıyla eklendi.';

    return redirect()->with('success', count($students) . ' ' . StudentMessages::BULK_IMPORTED->value);
}
}
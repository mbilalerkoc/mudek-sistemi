<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Services\StudentService;

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

        return redirect()->back()->with('success', 'Öğrenci başarıyla eklendi!');
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

        return redirect()->route('admin.students.index')->with('success', 'Öğrenci bilgileri güncellendi!');
    }

    public function destroy($id)
    {
        $this->studentRepository->delete($id);
        return redirect()->route('admin.students.index')->with('success', 'Öğrenci sistemden silindi!');
    }
}
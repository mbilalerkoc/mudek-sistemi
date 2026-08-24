<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class StudentsImport implements ToModel, WithHeadingRow, SkipsEmptyRows, SkipsOnError, WithValidation, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    private int $importedCount = 0;

    public function model(array $row): Student|null
    {

        $student = new Student([
            'student_no' => (string) $row['student_no'],
            'name'       => $row['name'],
            'surname'    => $row['surname'],
        ]);

        activity()
            ->performedOn($student)
            ->withProperties(['student_no' => $row['student_no']])
            ->log('Excel ile öğrenci eklendi');

        $this->importedCount++;
        return $student;
    }

    public function rules(): array
    {
        return [
            // unique kuralı ile veritabanındaki students tablosunun student_no sütununu kontrol ediyoruz
            'student_no' => 'required|unique:students,student_no',
            'name'       => 'required|string',
            'surname'    => 'required|string',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'student_no.required' => 'Öğrenci numarası boş olamaz.',
            'student_no.unique'   => 'Bu öğrenci numarası zaten sistemde kayıtlı.',
            'name.required'       => 'Ad alanı boş olamaz.',
            'surname.required'    => 'Soyad alanı boş olamaz.',
        ];
    }

    public function getImportedCount(): int 
    { 
        return $this->importedCount; 
    }
}
@extends('layouts.app')
@section('title', $course->name . ' - Notları Güncelle')

@section('content')
<div class="page-heading">
    <h3>{{ $course->name }} - Notları Güncelle</h3>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <p class="text-subtitle text-muted">{{ $course->code }} - Not Girişi</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.dersler') }}">Dersler</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.ders.detay', $course->id) }}">{{ $course->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Notları Güncelle</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">

        <div class="card shadow-sm">

            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 text-primary">{{ $course->name }} - Notları Güncelle</h4>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.ders.notlari.kaydet') }}" method="POST">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">

                    @php
                        $vize = $exams->first(fn ($exam) => strtolower(trim($exam->exam_type)) === 'midterm');
                        $final = $exams->first(fn ($exam) => strtolower(trim($exam->exam_type)) === 'final');
                        $butunleme = $exams->first(fn ($exam) => strtolower(trim($exam->exam_type)) === 'makeup');
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 120px;">Öğrenci No</th>
                                    <th class="text-start">Ad Soyad</th>
                                    <th style="width: 120px;">Vize</th>
                                    <th style="width: 120px;">Final</th>
                                    <th style="width: 120px;">Bütünleme</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    @php
                                        $studentExams = $student->studentExams;
                                        $vizeStudentExam = $vize ? $studentExams->where('exam_id', $vize->id)->first() : null;
                                        $finalStudentExam = $final ? $studentExams->where('exam_id', $final->id)->first() : null;
                                        $butunlemeStudentExam = $butunleme ? $studentExams->where('exam_id', $butunleme->id)->first() : null;
                                    @endphp
                                    <tr>
                                        <td class="fw-bold">{{ $student->student_no }}</td>
                                        <td class="text-start">{{ $student->name }} {{ $student->surname }}</td>

                                        {{-- DÜZELTME: exam_score yerine total_score gösteriliyor --}}
                                        <td>
                                            @if($vize)
                                                <input type="number" name="grades[midterm][{{ $student->id }}]"
                                                       value="{{ $vizeStudentExam->total_score ?? '' }}"
                                                       min="0" max="100" step="0.01"
                                                       class="form-control form-control-sm text-center" placeholder="Not">
                                            @else
                                                <span class="text-muted">Sınav yok</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($final)
                                                <input type="number" name="grades[final][{{ $student->id }}]"
                                                       value="{{ $finalStudentExam->total_score ?? '' }}"
                                                       min="0" max="100" step="0.01"
                                                       class="form-control form-control-sm text-center" placeholder="Not">
                                            @else
                                                <span class="text-muted">Sınav yok</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($butunleme)
                                                <input type="number" name="grades[makeup][{{ $student->id }}]"
                                                       value="{{ $butunlemeStudentExam->total_score ?? '' }}"
                                                       min="0" max="100" step="0.01"
                                                       class="form-control form-control-sm text-center" placeholder="Not">
                                            @else
                                                <span class="text-muted">Sınav yok</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-end">
                        <a href="{{ route('user.form.goster', ['ders_id' => $course->id, 'form_id' => 1]) }}" class="btn btn-secondary px-4 me-2">İptal</a>
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">
                            <i class="bi bi-check-lg me-1"></i>
                            Notları Kaydet
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </section>
</div>
@endsection
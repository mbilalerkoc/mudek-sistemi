@php
    $isAdmin      = auth()->user()->role === 'super_admin';
    $duzenleRoute = $isAdmin ? 'admin.ders.notlari.duzenle' : 'user.ders.notlari.duzenle';

    $vize       = $exams->first(fn($exam) => strtolower(trim($exam->exam_type)) === 'midterm');
    $final      = $exams->first(fn($exam) => strtolower(trim($exam->exam_type)) === 'final');
    $butunleme  = $exams->first(fn($exam) => strtolower(trim($exam->exam_type)) === 'makeup');
    $gradeService = app(\App\Services\GradeService::class);
@endphp

<div class="card shadow-sm">

    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0 text-primary">{{ $course->name }} - Öğrenci Notları</h4>

        <a href="{{ route($duzenleRoute, $course->id) }}" class="btn btn-warning px-4">
            <i class="bi bi-pencil-square me-1"></i>
            Notları Güncelle
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 120px;">Öğrenci No</th>
                        <th class="text-start">Ad Soyad</th>
                        <th style="width: 120px;">Vize</th>
                        <th style="width: 120px;">Final</th>
                        <th style="width: 120px;">Bütünleme</th>
                        <th style="width: 130px;">Ortalama</th>
                        <th style="width: 100px;">Harf Notu</th>
                        <th style="width: 110px;">Durum</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        @php
                            $studentCourse        = $student->studentCourses->where('course_id', $course->id)->first();
                            $ortalama             = $studentCourse->average ?? 0;
                            $harfNotu             = $studentCourse && $studentCourse->average !== null
                                                    ? $gradeService->harfNotuHesapla($studentCourse->average)
                                                    : '-';
                            $durum                = $studentCourse->status ?? '-';
                            $studentExams         = $student->studentExams;
                            $vizeStudentExam      = $vize      ? $studentExams->where('exam_id', $vize->id)->first()      : null;
                            $finalStudentExam     = $final     ? $studentExams->where('exam_id', $final->id)->first()     : null;
                            $butunlemeStudentExam = $butunleme ? $studentExams->where('exam_id', $butunleme->id)->first() : null;
                        @endphp
                        <tr>
                            <td class="fw-bold">{{ $student->student_no }}</td>
                            <td class="text-start">{{ $student->name }} {{ $student->surname }}</td>

                            <td>
                                @if($vize)
                                    {{ $vizeStudentExam->exam_score ?? '-' }}
                                @else
                                    <span class="text-muted">Sınav yok</span>
                                @endif
                            </td>

                            <td>
                                @if($final)
                                    {{ $finalStudentExam->exam_score ?? '-' }}
                                @else
                                    <span class="text-muted">Sınav yok</span>
                                @endif
                            </td>

                            <td>
                                @if($butunleme)
                                    {{ $butunlemeStudentExam->exam_score ?? '-' }}
                                @else
                                    <span class="text-muted">Sınav yok</span>
                                @endif
                            </td>

                            <td class="fw-bold text-primary">{{ number_format($ortalama, 2) }}</td>
                            <td><span class="badge bg-secondary px-2 py-1">{{ $harfNotu }}</span></td>
                            <td>
                                @if($durum === 'passed')
                                    <span class="badge bg-success">Geçti ✓</span>
                                @elseif($durum === 'failed')
                                    <span class="badge bg-danger">Kaldı ✗</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted py-4">Bu derse kayıtlı öğrenci bulunmamaktadır.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
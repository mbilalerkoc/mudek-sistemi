@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Öğrenci Notları</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('ders.notlari.kaydet') }}" method="POST">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Öğrenci No</th>
                            <th>Ad Soyad</th>
                            @foreach ($exams ?? [] as $exam)
                                <th class="text-center">
                                    {{ ucfirst($exam->exam_type) }}
                                    <span class="d-block text-muted" style="font-size: 11px;">
                                        {{ $exam->exam_date ? \Carbon\Carbon::parse($exam->exam_date)->format('d.m.Y') : '' }}
                                    </span>
                                </th>
                            @endforeach

                            <th class="text-center">Toplam Puan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->student_no }}</td>
                                <td>{{ $student->name }} {{ $student->surname }}</td>
                                
                                @foreach ($exams ?? [] as $exam)
                                    @php

                                        $studentExam = $student->studentExams->where('exam_id', $exam->id)->first();
                                    @endphp
                                    <td class="text-center">
                                        @if($studentExam)
                                            <input type="number" step="0.01" min="0" max="100"
                                                name="grades[{{ $studentExam->id }}][exam_score]"
                                                value="{{ $studentExam->exam_score ?? '' }}"
                                                class="form-control text-center">
                                        @else
                                            <span class="text-muted">Sınav tanımlı değil</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="text-center fw-bold text-primary">
                                    {{ $student->studentExams->sum('total_score') ?? 0 }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 3 + ($exams->count() ?? 0) }}" class="text-center text-muted">
                                    Bu derse kayıtlı öğrenci bulunmamaktadır.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($students->count() > 0)
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Notları Kaydet</button>
                </div>
            @endif

        </form>
    </div>
</div>

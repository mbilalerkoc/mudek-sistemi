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
                            <th>Vize</th>
                            <th>Final</th>
                            <th>Bütünleme</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            @php
                                // Öğrencinin bu derse ait pivot/ara tablosundaki notlarını çekiyoruz
                                $studentCourse = $student->studentCourses->where('course_id', $course->id)->first();
                            @endphp

                            <tr>
                                <td>{{ $student->student_no }}</td>
                                <td>{{ $student->name }}</td>
                                <td>
                                    <input type="number" name="grades[{{ $student->id }}][midterm]"
                                        class="form-control" min="0" max="100"
                                        value="{{ $studentCourse->midterm ?? '' }}">
                                </td>
                                <td>
                                    <input type="number" name="grades[{{ $student->id }}][final]" class="form-control"
                                        min="0" max="100" value="{{ $studentCourse->final ?? '' }}">
                                </td>
                                <td>
                                    <input type="number" name="grades[{{ $student->id }}][makeup]"
                                        class="form-control" min="0" max="100"
                                        value="{{ $studentCourse->makeup ?? '' }}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Bu derse kayıtlı öğrenci bulunamadı.</td>
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

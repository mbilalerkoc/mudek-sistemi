@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Öğrenci Sınav Kağıtları</h4>
    </div>
    <div class="card-body">

        <form>
            @csrf
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
                            <tr>
                                <td>{{ $student->student_no }}</td>
                                <td>{{ $student->name }}</td>
                                <td>
                                    <input type="file" id="dosya" name="dosya">
                                </td>
                                <td>
                                    <input type="file" id="dosya" name="dosya">
                                </td>
                                <td>
                                    <input type="file" id="dosya" name="dosya">
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
            <button type="submit" class="btn btn-primary">Dosyaları Kaydet</button>
        </form>

    </div>
</div>

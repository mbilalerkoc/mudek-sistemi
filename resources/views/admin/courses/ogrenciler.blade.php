@extends('layouts.app')

@section('title', $course->name . ' - Öğrenciler')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $course->name }} — Öğrenci Yönetimi</h3>
                <p class="text-subtitle text-muted">{{ $course->code }} — Derse kayıtlı ve kayıtsız öğrenciler</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Dersler</a></li>
                        <li class="breadcrumb-item active">Öğrenciler</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="section">


    <div class="row">

        {{-- SOL: Kayıtlı öğrenciler --}}
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Derse Kayıtlı Öğrenciler</span>
                    <span class="badge bg-success">{{ $kayitliOgrenciler->count() }} öğrenci</span>
                </div>
                <div class="card-body p-0">
                    <form action="{{ route('admin.courses.ogrenci.cikar.toplu', $course->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">
                                            {{-- Tümünü seç --}}
                                            <input type="checkbox" id="selectAllKayitli" class="form-check-input">
                                        </th>
                                        <th>Öğrenci No</th>
                                        <th>Ad Soyad</th>
                                        <th class="text-center">Durum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kayitliOgrenciler as $kayit)
                                        <tr>
                                            <td>
                                                <input type="checkbox"
                                                       name="student_ids[]"
                                                       value="{{ $kayit->student->id }}"
                                                       class="form-check-input kayitli-check">
                                            </td>
                                            <td class="fw-bold">{{ $kayit->student->student_no }}</td>
                                            <td>{{ $kayit->student->name }} {{ $kayit->student->surname }}</td>
                                            <td class="text-center">
                                                @if ($kayit->status === 'passed')
                                                    <span class="badge bg-success">Geçti</span>
                                                @elseif ($kayit->status === 'failed')
                                                    <span class="badge bg-danger">Kaldı</span>
                                                @else
                                                    <span class="badge bg-secondary">Devam</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                Kayıtlı öğrenci yok.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($kayitliOgrenciler->count() > 0)
                            <div class="p-3 border-top">
                                <button type="submit"
                                        class="btn btn-danger w-100"
                                        onclick="return confirm('Seçili öğrencileri dersten çıkarmak istediğine emin misin?')">
                                    <i class="bi bi-person-dash me-1"></i> Seçilileri Dersten Çıkar
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- SAĞ: Kayıtsız öğrenciler --}}
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Derse Kayıtsız Öğrenciler</span>
                    <span class="badge bg-secondary">{{ $kayitsizOgrenciler->count() }} öğrenci</span>
                </div>
                <div class="card-body p-0">
                    <form action="{{ route('admin.courses.ogrenci.ekle', $course->id) }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">
                                            {{-- Tümünü seç --}}
                                            <input type="checkbox" id="selectAllKayitsiz" class="form-check-input">
                                        </th>
                                        <th>Öğrenci No</th>
                                        <th>Ad Soyad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kayitsizOgrenciler as $ogrenci)
                                        <tr>
                                            <td>
                                                <input type="checkbox"
                                                       name="student_ids[]"
                                                       value="{{ $ogrenci->id }}"
                                                       class="form-check-input kayitsiz-check">
                                            </td>
                                            <td class="fw-bold">{{ $ogrenci->student_no }}</td>
                                            <td>{{ $ogrenci->name }} {{ $ogrenci->surname }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                Tüm öğrenciler bu derse kayıtlı.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($kayitsizOgrenciler->count() > 0)
                            <div class="p-3 border-top">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-person-plus me-1"></i> Seçilileri Derse Ekle
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-3">
        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Dersler Listesine Dön
        </a>
    </div>

</section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/custom/course-students.js') }}"></script>
@endpush
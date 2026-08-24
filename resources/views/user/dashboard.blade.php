@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="page-heading">

        {{-- Başlık --}}
        <div class="page-title mb-4">
            <div class="row align-items-center">

                <div class="col-12 col-md-8">
                    <h3>Hoş Geldiniz, {{ auth()->user()->name }}</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Üniversite yönetim sistemine genel bakış
                    </p>
                </div>

                <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-primary-light text-primary px-3 py-2">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ now()->format('d.m.Y') }}
                    </span>
                </div>

            </div>
        </div>

        <div class="page-content">

            {{-- İSTATİSTİK KARTLARI --}}
            <section class="row">

                {{-- Dersler --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body px-4 py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted font-semibold">Dersler</h6>
                                    <h3 class="font-extrabold mb-0">{{ $coursesCount ?? 0 }}</h3>
                                </div>
                                <div class="stats-icon purple">
                                    <i class="iconly-boldShow"></i>
                                </div>
                            </div>
                            <p class="text-muted mt-2 mb-0">Sistemdeki ders sayısı</p>
                        </div>
                    </div>
                </div>

                {{-- Öğrenciler --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body px-4 py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted font-semibold">Öğrenciler</h6>
                                    <h3 class="font-extrabold mb-0">{{ $studentsCount ?? 0 }}</h3>
                                </div>
                                <div class="stats-icon blue">
                                    <i class="iconly-boldProfile"></i>
                                </div>
                            </div>
                            <p class="text-muted mt-2 mb-0">Kayıtlı öğrenci sayısı</p>
                        </div>
                    </div>
                </div>

                {{-- Ödevler --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body px-4 py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted font-semibold">Ödevler</h6>
                                    <h3 class="font-extrabold mb-0">{{ $assignmentsCount ?? 0 }}</h3>
                                </div>
                                <div class="stats-icon green">
                                    <i class="iconly-boldDocument"></i>
                                </div>
                            </div>
                            <p class="text-muted mt-2 mb-0">Oluşturulan ödevler</p>
                        </div>
                    </div>
                </div>

                {{-- Sınavlar --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body px-4 py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted font-semibold">Sınavlar</h6>
                                    <h3 class="font-extrabold mb-0">{{ $examsCount ?? 0 }}</h3>
                                </div>
                                <div class="stats-icon red">
                                    <i class="iconly-boldPaper"></i>
                                </div>
                            </div>
                            <p class="text-muted mt-2 mb-0">Tanımlanan sınavlar</p>
                        </div>
                    </div>
                </div>

            </section>


            {{-- ANA İÇERİK (Dersler ve Hızlı İşlemler Yan Yana) --}}
            <section class="row">

                {{-- Dersler Listesi --}}
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="card-title mb-1">Dersler</h4>
                                    <p class="text-muted mb-0">Derslerin genel durumu</p>
                                </div>
                                @if (auth()->user()->role === 'super_admin')
                                    <a href="{{ route('admin.dersler') }}" class="btn btn-primary-light">Tüm Dersler</a>
                                @else
                                    <a href="{{ route('user.dersler') }}" class="btn btn-primary-light">Tüm Dersler</a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @forelse ($courses ?? [] as $course)
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <strong class="course-name">{{ $course->name }}</strong>
                                            @if ($course->code)
                                                <small class="course-code ms-2">{{ $course->code }}</small>
                                            @endif
                                        </div>
                                        <span class="fw-bold">%{{ $course->yuzde ?? 0 }}</span>
                                    </div>
                                    <div class="progress" style="height: 7px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $course->yuzde ?? 0 }}%;"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="bi bi-book fs-1 text-muted"></i>
                                    <p class="text-muted mt-3 mb-0">Henüz ders bulunmuyor.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script>
@endpush
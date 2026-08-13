@extends('layouts.app')

@section('title', 'Süper Admin Paneli - Konya Teknik Üniversitesi')

@section('content')
<div class="page-heading mb-4">
    <h3>Süper Admin Yönetim Paneli</h3>
    <p class="text-muted">Sistem genelindeki öğretmenleri ve dersleri bu alandan yönetebilirsiniz.</p>
</div>

<div class="page-content">
    {{-- İstatistik Kartları --}}
    <div class="row">
        <div class="col-6 col-lg-4 col-md-6">
            <div class="card shadow-sm">
                <div class="card-body px-4 py-4-5">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2 p-3 bg-primary text-white rounded-3">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Toplam Öğretmen</h6>
                            <h6 class="font-extrabold mb-0">{{ \App\Models\User::where('role', '!=', 'super_admin')->count() }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-4 col-md-6">
            <div class="card shadow-sm">
                <div class="card-body px-4 py-4-5">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2 p-3 bg-success text-white rounded-3">
                                <i class="bi bi-journal-bookmark-fill fs-4"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Toplam Ders</h6>
                            <h6 class="font-extrabold mb-0">{{ \App\Models\Course::count() }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hızlı İşlemler / Menü Yönlendirmeleri --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h4 class="card-title mb-0">Yönetim Kısayolları</h4>
                </div>
                <div class="card-body py-4 d-flex flex-wrap gap-3">
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-journal-plus me-2"></i> Dersleri Yönet ve Ata
                    </a>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary px-4 py-2">
                        <i class="bi bi-person-lines-fill me-2"></i> Öğretmenleri Listele
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
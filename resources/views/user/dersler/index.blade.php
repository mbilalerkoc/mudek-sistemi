@extends('layouts.app')

@section('title', 'Dersler')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Dersler</h3>
                    <p class="text-subtitle text-muted">
                        @if(auth()->user()->role === 'super_admin')
                            Sistemdeki tüm dersler
                        @else
                            Sorumlu olduğun dersler ve ders bilgi formları
                        @endif
                    </p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                @if(auth()->user()->role === 'super_admin')
                                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                @else
                                    <a href="{{ route('user.dashboard') }}">Dashboard</a>
                                @endif
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Dersler</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                @forelse ($courses ?? [] as $course)
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $course->name }}</h5>
                                <p class="text-muted mb-3">{{ $course->code }}</p>

                                {{-- Öğretmen bilgisi - sadece admin görür --}}
                                @if(auth()->user()->role === 'super_admin')
                                    <p class="text-muted mb-2" style="font-size: 0.82rem;">
                                        <i class="bi bi-person me-1"></i>
                                        @if($course->users->isNotEmpty())
                                            {{ $course->users->first()->name }} {{ $course->users->first()->surname }}
                                        @else
                                            <span class="text-warning">Öğretmen atanmamış</span>
                                        @endif
                                    </p>
                                @endif

                                <div class="mb-4">
                                    <div class="text-muted mb-2" style="font-size: 0.85rem;">
                                        {{ $course->toplam_form ?? 0 }} formdan
                                        {{ $course->doldurulan_form ?? 0 }} tanesi tamamlandı
                                    </div>
                                    <div class="progress mb-2"
                                        style="height: 6px; border-radius: 10px; background-color: #e9ecef;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $course->yuzde ?? 0 }}%; background-color: #28a745; border-radius: 10px;">
                                        </div>
                                    </div>
                                    <div class="fw-bold" style="font-size: 1rem; color: #1e293b;">
                                        %{{ $course->yuzde ?? 0 }} Tamamlandı
                                    </div>
                                </div>

                                {{-- Route role göre değişiyor --}}
                                @if(auth()->user()->role === 'super_admin')
                                    <a href="{{ route('admin.ders.detay', $course->id) }}"
                                       class="btn btn-primary-light">
                                        Formu Görüntüle
                                    </a>
                                @else
                                    <a href="{{ route('user.ders.detay', $course->id) }}"
                                       class="btn btn-primary-light">
                                        Formu Doldur
                                    </a>
                                @endif

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center" role="alert">
                            @if(auth()->user()->role === 'super_admin')
                                Sistemde henüz ders bulunmuyor.
                            @else
                                Sorumlu olduğunuz herhangi bir ders bulunamadı.
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
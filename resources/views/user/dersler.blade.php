@extends('layouts.app')

@section('title', 'Dersler')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Dersler</h3>
                    <p class="text-subtitle text-muted">Sorumlu olduğun dersler ve ders bilgi formları</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dersler</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                {{-- DİNAMİK DÖNGÜ (Gerçek Veriler İçin) --}}
                @forelse ($courses ?? [] as $course)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $course->name }}</h5>
                                <p class="text-muted mb-3">{{ $course->code }}</p>

                                {{-- İLERLEME ÇUBUĞU (Görseldeki Tasarım) --}}
                                <div class="mb-4">
                                    <div class="text-muted mb-2" style="font-size: 0.85rem;">
                                        {{ $course->toplam_form ?? 0 }} formdan {{ $course->doldurulan_form ?? 0 }} tanesi
                                        tamamlandı
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

                                <button type="button" class="btn btn-primary-light" data-bs-toggle="modal"
                                    data-bs-target="#dersModal{{ $course->id }}">
                                    Formu Doldur
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- STATİK DÖNGÜ (Örnek Tasarım Testi İçin) --}}
                    @php
                        $ornekDersler = [
                            [
                                'id' => 1,
                                'code' => 'BIL301',
                                'name' => 'Veri Tabanı Yönetim Sistemleri',
                                'toplam_form' => 17,
                                'doldurulan_form' => 10,
                                'yuzde' => 58,
                            ],
                            [
                                'id' => 2,
                                'code' => 'BIL402',
                                'name' => 'Yazılım Mühendisliği',
                                'toplam_form' => 5,
                                'doldurulan_form' => 0,
                                'yuzde' => 0,
                            ],
                            [
                                'id' => 3,
                                'code' => 'BIL205',
                                'name' => 'Web Programlama',
                                'toplam_form' => 8,
                                'doldurulan_form' => 8,
                                'yuzde' => 100,
                            ],
                        ];
                    @endphp

                    @foreach ($ornekDersler as $ders)
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $ders['name'] }}</h5>
                                    <p class="text-muted mb-3">{{ $ders['code'] }}</p>

                                    {{-- İLERLEME ÇUBUĞU (Görseldeki Tasarım) --}}
                                    <div class="mb-4">
                                        <div class="text-muted mb-2" style="font-size: 0.85rem;">
                                            {{ $ders['toplam_form'] }} formdan {{ $ders['doldurulan_form'] }} tanesi
                                            tamamlandı
                                        </div>

                                        <div class="progress mb-2"
                                            style="height: 6px; border-radius: 10px; background-color: #e9ecef;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $ders['yuzde'] }}%; background-color: #28a745; border-radius: 10px;">
                                            </div>
                                        </div>

                                        <div class="fw-bold" style="font-size: 1rem; color: #1e293b;">
                                            %{{ $ders['yuzde'] }} Tamamlandı
                                        </div>
                                    </div>

                                    <a href="{{ route('user.ders.detay', $ders['id']) }}" class="btn btn-primary">
                                        Formu Doldur
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </section>
    </div>
@endsection

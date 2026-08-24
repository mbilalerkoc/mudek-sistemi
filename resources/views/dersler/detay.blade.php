@extends('layouts.app')

@section('title', 'Ders Detay')

@section('content')

    @php
        $isAdmin = auth()->user()->role === 'super_admin';
        $dashboardRoute = $isAdmin ? 'admin.dashboard' : 'user.dashboard';
        $derslerRoute = $isAdmin ? 'admin.dersler' : 'user.dersler';
        $formRoute = $isAdmin ? 'admin.form.goster' : 'user.form.goster';
        $odevRoute = $isAdmin ? 'admin.dersler.odevler.index' : 'user.dersler.odevler.index';

        $formlar = [
            [
                'id' => 1,
                'ad' => 'Öğrenci Notları',
                'aciklama' => 'Vize, final ve bütünleme notlarını giriniz',
                'icon' => 'bi-journal-check',
                'sadece_admin' => true,
            ],
            [
                'id' => 2,
                'ad' => 'Sınavlar',
                'aciklama' => 'Sınav yönetimi ve soru ekleme işlemlerini yapınız',
                'icon' => 'bi-pencil-square',
                'sadece_admin' => false,
            ],
            [
                'id' => 3,
                'ad' => 'Ödev Yükleme',
                'aciklama' => 'Ödev dosyalarını yükleyiniz',
                'icon' => 'bi-upload',
                'sadece_admin' => false,
            ],
        ];
    @endphp

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $course->name }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ $course->code }} — Forma tıklayarak doldurmaya başlayabilirsin
                    </p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route($dashboardRoute) }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route($derslerRoute) }}">Dersler</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Ders Detay</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row g-4">
            @foreach ($formlar as $form)
                @if ($form['sadece_admin'] && !$isAdmin)
                    @continue
                @endif

                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route($formRoute, ['ders_id' => $course->id, 'form_id' => $form['id']]) }}"
                        class="text-decoration-none">
                        <div class="card h-100" style="transition: transform 0.15s ease, box-shadow 0.15s ease;"
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.1)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mt-1"
                                        style="width:56px; height:56px; flex-shrink:0; background: var(--ktun-primary-dark);">
                                        <i class="bi {{ $form['icon'] }}"
                                            style="font-size:1.5rem; color: var(--ktun-primary);"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1">{{ $form['ad'] }}</h5>
                                        <p class="mb-0 text-muted" style="font-size:0.82rem; line-height:1.3;">
                                            {{ $form['aciklama'] }}
                                        </p>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <span class="btn btn-sm btn-primary-light">
                                        {{ $isAdmin ? 'Görüntüle' : 'Doldur' }}
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endsection
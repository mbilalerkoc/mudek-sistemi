@extends('layouts.app')

@section('title', 'Ders Detay')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $ders->ders_adi }}</h3>
                <p class="text-subtitle text-muted">{{ $ders->ders_kodu }} — Forma tıklayarak doldurmaya başlayabilirsin</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.dersler') }}">Dersler</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ders Detay</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">Formlar</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Form Adı</th>
                            <th>Açıklama</th>
                            <th class="text-center">Durum</th>
                            <th class="text-center">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $ornekFormlar = [
                                ['id' => 1, 'ad' => 'Program Çıktılarına Katkı Formu', 'aciklama' => 'Vize ve final ağırlıklarını belirtiniz', 'tamamlandi' => true],
                                ['id' => 2, 'ad' => 'Ders Öğrenme Çıktıları Formu', 'aciklama' => 'Öğrencilerin kazanacağı yetkinlikler', 'tamamlandi' => false],
                            ];
                        @endphp

                        @foreach ($ornekFormlar as $form)
                            <tr>
                                <td class="fw-500">{{ $form['ad'] }}</td>
                                <td class="text-muted">{{ $form['aciklama'] }}</td>
                                <td class="text-center">
                                    @if ($form['tamamlandi'])
                                        <span class="badge bg-success">Tamamlandı</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Bekliyor</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- ROUTE EKLENDİ: Hangi dersten, hangi forma gidildiği parametre olarak veriliyor --}}
                                    <a href="{{ route('user.form.goster', ['ders_id' => $ders->id, 'form_id' => $form['id']]) }}" 
                                       class="btn btn-sm {{ $form['tamamlandi'] ? 'btn-outline-secondary' : 'btn-primary' }}">
                                        {{ $form['tamamlandi'] ? 'Düzenle' : 'Doldur' }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
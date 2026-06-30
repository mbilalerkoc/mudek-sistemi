@extends('layouts.app')

{{-- Dizi kullanımı yerine nesne kullanımı (->) getirildi ve değişken adları güncellendi --}}
@section('title', $seciliDers->ders_adi . ' Formu')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                {{-- name yerine ders_adi, code yerine ders_kodu --}}
                <h3>{{ $seciliDers->ders_adi }}</h3>
                <p class="text-subtitle text-muted">{{ $seciliDers->ders_kodu }} - Ders Bilgi Formu</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') ?? '#' }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.dersler') ?? '#' }}">Dersler</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Form</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">Ders Katkı Puanlarını Giriniz</h4>
            </div>
            <div class="card-body pt-4">
                @include('layouts.partials.ders-form')
            </div>
        </div>
    </section>
</div>
@endsection
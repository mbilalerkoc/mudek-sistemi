@extends('layouts.app')
@section('title', $course->name . ' Formu')

@section('content')
    <div class="page-heading">
        <h3>{{ $course->name }} Formu</h3>
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <p class="text-subtitle text-muted">{{ $course->code }} - Ders Bilgi Formu</p>
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
                            <li class="breadcrumb-item">
                                <a href="{{ route($detayRoute, $course->id) }}">{{ $course->name }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Form</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            @if ($form_id == 1)
                @include('dersler.forms.notlar.notlar')
            @elseif($form_id == 2)
                @include('dersler.forms.sinavlar.sinav-kagitlari')
            @elseif($form_id == 3)
                @include('dersler.forms.odevler.odevler')
            @else
                <div class="alert alert-warning">Form bulunamadı.</div>
            @endif
        </section>
    </div>
@endsection

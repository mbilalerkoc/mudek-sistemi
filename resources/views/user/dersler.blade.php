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
            {{--
                Controller'dan $courses adında bir koleksiyon gönderdiğinde
                (örn: Course::where('instructor_id', auth()->id())->get())
                bu @forelse otomatik olarak gerçek dersleri listeleyecek.
                Şimdilik veri yoksa aşağıdaki @empty bloğu 3 örnek ders gösterir.
            --}}
            @forelse ($courses ?? [] as $course)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $course->name }}</h5>
                            <p class="text-muted mb-3">{{ $course->code }}</p>
                            <button type="button" class="btn btn-primary-light" data-bs-toggle="modal" data-bs-target="#dersModal{{ $course->id }}">
                                Formu Doldur
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Bu derse ait modal --}}
                <div class="modal fade" id="dersModal{{ $course->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $course->name }} — Ders Bilgi Formu</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @include('partials.ders-form')
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Henüz controller'dan veri gönderilmediği için örnek (göstermelik) dersler --}}
                @php
                    $ornekDersler = [
                        ['id' => 1, 'code' => 'BIL301', 'name' => 'Veri Tabanı Yönetim Sistemleri'],
                        ['id' => 2, 'code' => 'BIL402', 'name' => 'Yazılım Mühendisliği'],
                        ['id' => 3, 'code' => 'BIL205', 'name' => 'Web Programlama'],
                    ];
                @endphp

                @foreach ($ornekDersler as $ders)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $ders['name'] }}</h5>
                                <p class="text-muted mb-3">{{ $ders['code'] }}</p>
                                <button type="button" class="btn btn-primary-light" data-bs-toggle="modal" data-bs-target="#dersModal{{ $ders['id'] }}">
                                    Formu Doldur
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="dersModal{{ $ders['id'] }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ $ders['name'] }} — Ders Bilgi Formu</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @include('layouts.partials.ders-form')
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>
    </section>
</div>
@endsection
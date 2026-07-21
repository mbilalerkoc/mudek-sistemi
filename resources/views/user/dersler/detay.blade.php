@extends('layouts.app')

@section('title', 'Ders Detay')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $course->name }}</h3>
                    <p class="text-subtitle text-muted">{{ $course->code }} — Forma tıklayarak doldurmaya başlayabilirsin</p>
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
                                <th class="text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $formlar = [
                                    [
                                        'id'       => 1,
                                        'ad'       => 'Öğrenci Notları',
                                        'aciklama' => 'Vize, final ve bütünleme notlarını giriniz',
                                    ],
                                    [
                                        'id'       => 2,
                                        'ad'       => 'Sınav Kağıtları',
                                        'aciklama' => 'Sınav soruları ve cevap anahtarını yükleyiniz',
                                    ],
                                    [
                                        'id'       => 3,
                                        'ad'       => 'Ödev Yükleme',
                                        'aciklama' => 'Ödev dosyalarını yükleyiniz',
                                    ],
                                    [
                                        'id'       => 4,
                                        'ad'       => 'Öğrenci Kağıtları',
                                        'aciklama' => 'En iyi, orta ve kötü seviye kağıtlarını yükleyiniz',
                                    ],
                                ];
                            @endphp

                            @foreach($formlar as $form)
                                <tr>
                                    <td class="fw-500">{{ $form['ad'] }}</td>
                                    <td class="text-muted">{{ $form['aciklama'] }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('user.form.goster', ['ders_id' => $course->id, 'form_id' => $form['id']]) }}"
                                           class="btn btn-sm btn-primary-light">
                                            Doldur
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
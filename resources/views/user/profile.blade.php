@extends('layouts.app')

@section('title', 'Profilim')

@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Hesap Profili</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <div class="ktun-sidebar-avatar">
                                    <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Profil">
                                </div>
                                <h3 class="mt-3">John Doe</h3>
                                <p class="text-small">Junior Software Engineer</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="#" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name" class="form-label">İsim</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Your Name" value="John Doe">
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Your Email" value="john.doe@example.net">
                                </div>

                                <div class="form-group">
                                    <label for="current_password" class="form-label">Mevcut Şifre</label>
                                    <input type="password" name="current_password" id="current_password"
                                        class="form-control" placeholder="Mevcut Şifreniz">
                                </div>

                                <div class="form-group">
                                    <label for="new_password" class="form-label">Yeni Şifre</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control"
                                        placeholder="Yeni Şifre">
                                </div>

                                <div class="form-group">
                                    <label for="new_password_confirmation" class="form-label">Yeni Şifre Tekrar</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                        class="form-control" placeholder="Yeni Şifre Tekrar">
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary-light">Kaydet</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

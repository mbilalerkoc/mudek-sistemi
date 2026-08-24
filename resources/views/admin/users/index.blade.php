@extends('layouts.app')

@section('title', 'Kullanıcı ve Öğretmen Yönetimi')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h3>Sistem Kullanıcıları</h3>
            <p class="text-subtitle text-muted">Öğretmen, öğrenci ve admin hesaplarını buradan yönetebilirsiniz.</p>
        </div>
        <a href="{{ route('admin.users.ekle') }}" class="btn btn-primary-light">
            <i class="bi bi-person-plus-fill me-2"></i> Yeni Kullanıcı Ekle
        </a>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">Kullanıcı Listesi</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Unvan / Ad Soyad</th>
                                <th>E-posta</th>
                                <th>Rol</th>
                                <th class="text-end">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ optional($user->academicTitle)->title }} {{ $user->name }} {{ $user->surname }}</span>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->role === 'super_admin')
                                            <span class="badge bg-ktun-soft text-danger">Süper Admin</span>
                                        @elseif($user->role === 'user')
                                            <span class="badge bg-ktun-soft text-primary">Kullanıcı</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Düzenle">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                  class="d-inline-block"
                                                  onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Sil">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
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
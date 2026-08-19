@extends('layouts.app')

@section('title', 'Kullanıcı ve Öğretmen Yönetimi')

@section('content')
    <div class="page-heading mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3>Sistem Kullanıcıları</h3>
            <p class="text-muted">Öğretmen, öğrenci ve admin hesaplarını buradan yönetebilirsiniz.</p>
        </div>
        <a href="{{ route('admin.users.ekle') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill me-2"></i> Yeni Kullanıcı Ekle
        </a>
    </div>

    <div class="page-content">

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
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
                                        <strong>{{ optional($user->academicTitle)->title }} {{ $user->name }}
                                            {{ $user->surname }}</strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->role === 'super_admin')
                                            <span class="badge bg-danger">Süper Admin</span>
                                        @elseif($user->role === 'user')
                                            <span class="badge bg-info">Kullanıcı</span>
                                        @else
                                            {{-- Eğer önceden kalma eski bir rol (student vb.) varsa doğrudan onu yazdırır, böylece fark edip silebilir veya güncelleyebilirsin --}}
                                            <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

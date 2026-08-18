@extends('layouts.app')

@section('title', 'Oturum Geçmişi')

@section('content')
<div class="page-heading">
    <h3>Kullanıcı Oturum Geçmişi</h3>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Kullanıcı</th>
                            <th>IP Adresi</th>
                            <th>Giriş Zamanı</th>
                            <th>Çıkış Zamanı</th>
                            <th>Oturum Süresi</th>
                            <th>Tarayıcı</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr>
                                <td><strong>{{ $session['user']->name ?? 'Bilinmiyor' }} {{ $session['user']->surname ?? '' }}</strong></td>
                                <td><code>{{ $session['ip'] }}</code></td>
                                <td>{{ $session['login_at'] ? $session['login_at']->format('d.m.Y H:i:s') : '-' }}</td>
                                <td>
                                    @if($session['logout_at'])
                                        {{ $session['logout_at']->format('d.m.Y H:i:s') }}
                                    @else
                                        <span class="badge bg-success">Aktif Oturum</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $session['duration'] }}</span>
                                </td>
                                <td><span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $session['user_agent'] }}">{{ $session['user_agent'] }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Henüz kayıtlı oturum geçmişi bulunmuyor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
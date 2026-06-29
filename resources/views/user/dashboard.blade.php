@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')

{{-- Mazer'in index.html'inden page-heading ve page-content bloğunu BURAYA yapıştır --}}
<div class="page-heading">
    <h3>Profile Statistics</h3>
</div>
<div class="page-content">
    <section class="row">
        {{-- Mazer'deki tüm card, chart, table HTML'leri buraya olduğu gibi --}}
    </section>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script>
@endpush
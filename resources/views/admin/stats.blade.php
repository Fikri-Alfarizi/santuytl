@extends('layouts.admin')
@section('title', 'Statistik')
@section('content')
<div class="container">
    <h1>Statistik Komunitas</h1>
    <ul>
        <li>Total User: {{ $totalUsers }}</li>
        <li>Total XP: {{ $totalXp }}</li>
        <li>User Aktif 7 Hari: {{ $activeUsers }}</li>
    </ul>
    <!-- Tambahkan grafik/chart jika ingin -->
</div>
@endsection

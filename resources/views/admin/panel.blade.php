@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Admin Panel</h1>
    <div class="alert alert-info">Selamat datang, {{ $user->name }}! Anda memiliki akses admin/moderator.</div>
    <ul>
        <li><a href="{{ route('tickets.index') }}">Manajemen Tiket Support</a></li>
        <li><a href="{{ route('games.index') }}">Manajemen Game</a></li>
        <li><a href="{{ route('events.index') }}">Manajemen Event</a></li>
        <li><a href="{{ route('staff.index') }}">Manajemen Staff</a></li>
        <!-- Tambahkan menu admin lain di sini -->
    </ul>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Daftar Pengguna')

@section('content')
<div class="container-fluid">
    <h1>Daftar Pengguna</h1>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Level</th>
                    <th>XP</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user['username'] ?? $user['name'] ?? '-' }}</td>
                        <td><span class="badge" style="background-color: var(--primary-color);">{{ $user['role'] ?? '-' }}</span></td>
                        <td>{{ $user['level'] ?? '-' }}</td>
                        <td>{{ $user['xp'] ?? 0 }}</td>
                        <td>{{ isset($user['joined_at']) && $user['joined_at'] ? date('d M Y', strtotime($user['joined_at'])) : '-' }}</td>
                        <td>
                            <a href="{{ route('admin.users.details', $user['id']) }}" class="btn btn-primary btn-sm">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- Pagination di-nonaktifkan karena data dari API Discord --}}
</div>
@endsection
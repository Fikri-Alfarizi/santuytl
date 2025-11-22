@extends('layouts.app')

@section('title', 'Permintaan Game')

@section('content')
<div class="container">
    <h1 class="mb-4">Permintaan Game</h1>
    <a href="{{ route('requests.create') }}" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Buat Permintaan Baru</a>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>{{ $request['content'] ?? '-' }}</td>
                            <td><span class="badge bg-success">Terkirim</span></td>
                            <td>{{ isset($request['timestamp']) ? date('d F Y', $request['timestamp']/1000) : '-' }}</td>
                            <td>
                                @if(isset($request['id']))
                                    <a href="https://discord.com/channels/1385911366854115432/1385912786395336875/{{ $request['id'] }}" target="_blank" class="btn btn-sm btn-primary">Lihat di Discord</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Belum ada permintaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

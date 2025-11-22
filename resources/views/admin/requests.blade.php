@extends('layouts.admin')
@section('title', 'Permintaan Game (Admin)')
@section('content')
<div class="container">
    <h1 class="mb-4">Permintaan Game (Admin)</h1>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->title }}</td>
                            <td>{{ ucfirst($request->status) }}</td>
                            <td>{{ $request->created_at->format('d F Y') }}</td>
                            <td>
                                <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <form action="{{ route('admin.requests.update', $request->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm d-inline w-auto">
                                        <option value="pending" @if($request->status=='pending') selected @endif>Pending</option>
                                        <option value="approved" @if($request->status=='approved') selected @endif>Disetujui</option>
                                        <option value="rejected" @if($request->status=='rejected') selected @endif>Ditolak</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">Belum ada permintaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

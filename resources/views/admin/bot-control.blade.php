@extends('layouts.admin')
@section('title', 'Kontrol Bot Discord')
@section('content')
<div class="container">
    <h1>Kontrol Bot Discord</h1>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h4>Kirim Pesan ke Channel</h4>
                <form method="POST" action="{{ route('admin.discord.send-message') }}">
                    @csrf
                    <div class="mb-2">
                        <label>Channel ID</label>
                        <input type="text" name="channel_id" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Pesan</label>
                        <textarea name="message" class="form-control" required></textarea>
                    </div>
                    <button class="btn btn-primary"><i class="fab fa-discord"></i> Kirim Pesan</button>
                </form>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h4>Kirim DM ke User</h4>
                <form method="POST" action="{{ route('admin.discord.send-dm') }}">
                    @csrf
                    <div class="mb-2">
                        <label>User Discord ID</label>
                        <input type="text" name="user_id" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Pesan</label>
                        <textarea name="message" class="form-control" required></textarea>
                    </div>
                    <button class="btn btn-info"><i class="fas fa-paper-plane"></i> Kirim DM</button>
                </form>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h4>Kick/Ban User</h4>
                <form method="POST" action="{{ route('admin.discord.kick-ban') }}">
                    @csrf
                    <div class="mb-2">
                        <label>User Discord ID</label>
                        <input type="text" name="user_id" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Aksi</label>
                        <select name="action" class="form-control" required>
                            <option value="kick">Kick</option>
                            <option value="ban">Ban</option>
                        </select>
                    </div>
                    <button class="btn btn-danger"><i class="fas fa-user-slash"></i> Eksekusi</button>
                </form>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h4>Assign/Remove Role</h4>
                <form method="POST" action="{{ route('admin.discord.role') }}">
                    @csrf
                    <div class="mb-2">
                        <label>User Discord ID</label>
                        <input type="text" name="user_id" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Role Name</label>
                        <input type="text" name="role" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Aksi</label>
                        <select name="action" class="form-control" required>
                            <option value="assign">Assign</option>
                            <option value="remove">Remove</option>
                        </select>
                    </div>
                    <button class="btn btn-warning"><i class="fas fa-user-tag"></i> Proses</button>
                </form>
            </div>
        </div>
        <div class="col-md-12 mb-4">
            <div class="card p-3">
                <h4>Status Bot</h4>
                <form method="GET" action="{{ route('admin.discord.status') }}">
                    <button class="btn btn-success"><i class="fas fa-info-circle"></i> Cek Status Bot</button>
                </form>
                @if(session('bot_status'))
                    <div class="alert alert-info mt-2">{{ session('bot_status') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Pembayaran VIP')

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="fas fa-credit-card"></i> Pembayaran VIP</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h4>Detail Pembelian</h4>
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Durasi:</strong> {{ $purchase->days }} hari</li>
                <li class="list-group-item"><strong>Harga:</strong> Rp {{ number_format($purchase->amount, 0, ',', '.') }}</li>
                <li class="list-group-item"><strong>Metode Pembayaran:</strong> {{ strtoupper($purchase->payment_method) }}</li>
                <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($purchase->status) }}</li>
            </ul>
            <form action="{{ route('vip.confirm-payment', $purchase->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="fas fa-check"></i> Konfirmasi Pembayaran
                </button>
            </form>
        </div>
    </div>
    <a href="{{ route('vip.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Status VIP</a>
</div>
@endsection

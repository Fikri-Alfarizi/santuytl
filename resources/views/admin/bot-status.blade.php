@extends('layouts.admin')
@section('title', 'Status Bot Discord')
@section('content')
<div class="container">
    <h2>Status Bot Discord</h2>
    @if($status['online'] ?? false)
        <div class="alert alert-success">Bot Online sebagai <b>{{ $status['tag'] }}</b></div>
    @else
        <div class="alert alert-danger">Bot Offline</div>
    @endif
</div>
@endsection

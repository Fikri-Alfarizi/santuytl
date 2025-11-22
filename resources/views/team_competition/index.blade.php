@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Team Competitions</h1>
    <ul class="list-group mb-3">
        @foreach($competitions as $competition)
            <li class="list-group-item">
                <a href="{{ route('competition.show', $competition->id) }}">{{ $competition->name }}</a>
                <span class="badge badge-info">{{ $competition->start_at->format('d M Y') }} - {{ $competition->end_at->format('d M Y') }}</span>
            </li>
        @endforeach
    </ul>
</div>
@endsection

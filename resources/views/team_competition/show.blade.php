@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $competition->name }}</h1>
    <h4>Scores:</h4>
    <ul class="list-group mb-3">
        @foreach($competition->scores as $score)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $score->team->name }}
                <span class="badge badge-success">{{ $score->score }}</span>
            </li>
        @endforeach
    </ul>
    <a href="{{ route('competition.index') }}" class="btn btn-secondary">Back to Competitions</a>
</div>
@endsection

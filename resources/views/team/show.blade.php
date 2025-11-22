@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $team->name }}</h1>
    <h4>Members:</h4>
    <ul class="list-group mb-3">
        @foreach($team->users as $user)
            <li class="list-group-item">{{ $user->name }}</li>
        @endforeach
    </ul>
    @if(auth()->check() && !$team->users->contains(auth()->id()))
        <form method="POST" action="{{ route('team.join', $team->id) }}">
            @csrf
            <button class="btn btn-success">Join Team</button>
        </form>
    @elseif(auth()->check() && $team->users->contains(auth()->id()))
        <form method="POST" action="{{ route('team.leave', $team->id) }}">
            @csrf
            <button class="btn btn-danger">Leave Team</button>
        </form>
    @endif
    <a href="{{ route('team.index') }}" class="btn btn-secondary mt-3">Back to Teams</a>
</div>
@endsection

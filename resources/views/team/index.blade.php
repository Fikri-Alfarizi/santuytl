@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Teams</h1>
    <a href="{{ route('team.create') }}" class="btn btn-primary mb-3">Create Team</a>
    <ul class="list-group">
        @foreach($teams as $team)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('team.show', $team->id) }}">{{ $team->name }}</a>
                <span class="badge badge-info">{{ $team->users_count }} members</span>
            </li>
        @endforeach
    </ul>
</div>
@endsection

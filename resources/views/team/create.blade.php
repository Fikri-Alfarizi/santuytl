@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Team</h1>
    <form method="POST" action="{{ route('team.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Team Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Create</button>
    </form>
    <a href="{{ route('team.index') }}" class="btn btn-secondary mt-3">Back to Teams</a>
</div>
@endsection

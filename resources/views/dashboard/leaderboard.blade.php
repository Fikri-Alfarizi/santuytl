@extends('layouts.app')
@section('title', 'Papan Peringkat')
@section('content')
<div class="container">
    <h1 class="text-center mb-4"><i class="fas fa-trophy"></i> Papan Peringkat</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <table class="table">
                <thead>
                    <tr>
                        <th>Peringkat</th>
                        <th>Username</th>
                        <th>Level</th>
                        <th>XP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topUsers as $index => $userStat)
                        <tr>
                            <td>
                                @if($index == 0) <i class="fas fa-medal text-warning"></i>
                                @elseif($index == 1) <i class="fas fa-medal text-secondary"></i>
                                @elseif($index == 2) <i class="fas fa-medal" style="color:#cd7f32;"></i>
                                @else {{ $index + 1 }}
                                @endif
                            </td>
                            <td>{{ $userStat->user->name }}</td>
                            <td>{{ $userStat->user->level }}</td>
                            <td>{{ $userStat->xp }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

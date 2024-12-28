@extends('layout')

@section('title', 'Administration')

@section('content')
    <h2>Administration</h2>
    <a href="{{ route('admin.users.index') }}">
        <div class="card-section">
            <i class="fa-solid fa-users"></i> Users
        </div>
    </a>
    <a href="{{ route('admin.channels.index') }}">
        <div class="card-section">
            <i class="fab fa-youtube"></i> Channels
        </div>
    </a>
@endsection

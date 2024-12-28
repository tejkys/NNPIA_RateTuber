@extends('layout')

@section('title', 'Account')

@section('content')
    @if(Auth::check())
        <h2>Welcome</h2>
        <p>You are logged in as {{ Auth::user()->name }}.</p>
        <p>
        <ul class="list-inline">
            @if(Auth::user()->role->name == "admin")
                <li><a href="{{ route("admin.index") }}" title="Admin"><i class="fas fa-tools"></i></a></li>
            @endif
            <li><a href="#" title="Update password" class="toggle-button" data-for="user-password"><i
                        class="fas fa-key"></i></a></li>
            <li><a href="#" title="Your reviews" class="toggle-button" data-for="user-reviews"><i
                        class="fa-solid fa-comment"></i></a></li>
        </ul></p>
        <div class="card-section hidden" id="user-password">
            <h3>Change Password</h3>
            <form action={{route('account.changePassword')}} method="post" class="form-style form-inline">
                @csrf
                <input type="password" name="password" placeholder="password" required><br/>
                <input type="submit" value="update">
            </form>
        </div>
        <div id="user-reviews" class="hidden">
            <h2>Your reviews</h2>
            @forelse(Auth::user()->comments()->with('channel')->orderByDesc('updated_at')->get() as $commnet)
                <div class="card-section" style="text-align: left;">

                    <a href="{{route('channel.get', [$commnet->channel->name])}}"><i
                            class="far fa-comment"></i> {{ $commnet->channel->name }}
                        at {{ $commnet->updated_at->format('H:i:s d.m.Y') }}</a>
                </div>
            @empty
                <div class="card-section">
                    No comments yet
                </div>
            @endforelse
        </div>
        <br/>
        <a href="{{ route('account.logout') }}"> Logout</a>
    @else
        <h3>Login</h3>
        <div class="card-section">
            <form action={{route('account.login')}} method="post" class="form-style">
                @csrf
                <input type="email" name="email" placeholder="email" required>
                <input type="password" name="password" placeholder="password" required><br/>
                <input type="submit">
            </form>
        </div>
        <h3>or create an account</h3>
        <div class="card-section">
            <form action="{{route('account.create')}}" method="post" class="form-style">
                @csrf
                <input type="text" name="nickname" placeholder="nickname" minlength="3" maxlength="16" required>
                <input type="email" name="email" placeholder="email" required>
                <input type="password" name="password" placeholder="password" minlength="6" maxlength="32"
                       required><br/>
                <input type="submit">
            </form>
        </div>
    @endif
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" href="{{URL::asset('/favicon.ico')}}" type="image/x-icon">
        <title>{{ env('APP_NAME') }} - @yield('title')</title>

        <link rel="stylesheet" href="{{URL::asset('lib/jquery-ui-1.13.1/jquery-ui.min.css')}}">
        <script src="{{URL::asset('/lib/jquery-ui-1.13.1/external/jquery/jquery.js')}}"></script>
        <script src="{{URL::asset('/lib/jquery-ui-1.13.1/jquery-ui.min.js')}}"></script>

        <script src="https://kit.fontawesome.com/935ca818bb.js" crossorigin="anonymous"></script>
        <script src="{{URL::asset('/js/app.js')}}"></script>
        <link rel="stylesheet" href="{{URL::asset('/css/app.css')}}">

    </head>
    <body>
        <div class="container">
                <div class="card-container">
                    <form class="form-inline" method="post" action="{{ route('home.search') }}">
                        <a href="{{ route('home.index') }}"><img width="48px" src="{{ URL::asset('/images/icon.png') }}" style="margin-right: 15px"></a>
                        @csrf
                        <input type="text" name="search" placeholder="YouTube Username" required>
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        <a class="account-link" title="Account" href="{{ route('account.index') }}"><i class="far fa-user-circle"></i>
                        </a>
                    </form>

                </div>
                <div class="card-container">

                @if($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="message">{{ $error }}</div>
                        @endforeach
                    @endif
                    @if(session()->has('success'))
                        @foreach (session('success') as $message)
                            <div class="message">{{ $message }}</div>
                        @endforeach
                    @endif
                    @yield('content')
                </div>
        </div>
    </body>
</html>

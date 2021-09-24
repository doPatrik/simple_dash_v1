<?php /** @var \App\Http\Components\DataGetter $dataGetter */ ?>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/v4-shims.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @auth
            @include('layouts.side_menu')
            <div class="main-container">
                <header>
                    <div class="left-section">
                        <div class="menu-toggle">
                            <i class="fas fa-bars"></i>
                        </div>

                        @if(auth()->user()->addresses->count() > 1)
                            <div class="header-dropdown">
                                <a id="addressDropdown" class="menuItem" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span>{{session('address_name', 'Összes lakcím')}}</span>
                                    <i class="fas fa-sort-down"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="addressDropdown">
                                    <a class="dropdown-item" href="{{ route('address.select', -1) }}">
                                        {{ __('Összes lakcím') }}
                                    </a>
                                    @foreach(auth()->user()->addresses as $address)
                                    <a class="dropdown-item" href="{{ route('address.select', $address->id_address) }}">
                                        {{ __($address->city) }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="header-menu">
                        <notification-component></notification-component>

                        <div class="header-dropdown">
                            <a id="navbarDropdown" class="menuItem" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-user-circle"></i>
                                <span>{{ Auth::user()->last_name }} {{Auth::user()->first_name}}</span>
                                <i class="fas fa-sort-down"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                    {{ __('Kijelentkezés') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <main>
                    @yield('content')
                </main>
            </div>
        @endauth
    </div>
</body>
</html>

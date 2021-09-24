<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bejelentkezés - SimpleDash</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->

    <!-- Styles -->
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/v4-shims.css">
</head>
<body>
    <div class="__container" id="app">
        <div class="imageContainer">
            <img src="/image/login_cover.jpg" alt="bejelentkezés">
        </div>
        <div class="loginContainer">
            <div class="formContainer">
                @include('inc.messages')
                <h2>Bejelenetkezés</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="inputBox userName">
                        <div class="icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="inputContainer">
                            <h5>E-mail</h5>
                            <input type="text" class="input" name="email" required autocomplete="off">
                        </div>
                    </div>
                    <div class="inputBox password">
                        <div class="icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="inputContainer">
                            <h5>Jelszó</h5>
                            <input type="password" class="input" name="password" required autocomplete="off">
                        </div>
                    </div>
                    <div class="remember">
                        <!--<label><input type="checkbox" name=""> Emlékezz rám</label>-->
                        <a href="{{route('register')}}">Regisztráció</a>
                    </div>
                    <div>
                        <input type="submit" class="submit" value="Bejelentkezés">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

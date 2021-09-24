<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Regisztráció - SimpleDash</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->

    <!-- Styles -->
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/v4-shims.css">
</head>
<body>
<div class="__container">
    <div class="imageContainer">
        <img src="/image/login_cover.jpg" alt="bejelentkezés">
    </div>
    <div class="loginContainer">
        <div class="formContainer">
            <h2>Regisztráció</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="inputBox userName">
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="inputContainer">
                        <h5>Felhasználónév</h5>
                        <input type="text" class="input" name="name" value="{{ old('name') }}" required autocomplete="off">
                    </div>
                </div>
                <div class="inputBox lastName">
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="inputContainer">
                        <h5>Családnév</h5>
                        <input type="text" class="input" name="last_name" value="{{ old('last_name') }}" required autocomplete="off">
                    </div>
                </div>
                <div class="inputBox firstName">
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="inputContainer">
                        <h5>Keresztnév</h5>
                        <input type="text" class="input" name="first_name" value="{{ old('first_name') }}" required autocomplete="off">
                    </div>
                </div>
                <div class="inputBox email">
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="inputContainer">
                        <h5>E-mail</h5>
                        <input type="text" class="input" name="email" value="{{ old('email') }}" required autocomplete="off">
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
                <div class="inputBox passwordConfirm">
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="inputContainer">
                        <h5>Jelszó megerősítése</h5>
                        <input type="password" class="input" name="password_confirmation" required autocomplete="off">
                    </div>
                </div>
                <div>
                    <input type="submit" class="submit" value="Regisztráció">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion — F&A Viking</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&family=Cinzel:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-logo">
            <span class="logo-rune">ᚠ</span>
            <h1>Florent & Aurélie</h1>
            <p>Entrez dans la légende</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                @error('password') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn btn-primary" style="align-self:stretch; text-align:center;">
                ⚔️ Se connecter
            </button>
        </form>

        <div class="auth-footer">
            @if(Route::has('password.request'))
                <p><a href="{{ route('password.request') }}">Mot de passe oublié ?</a></p>
            @endif
            <p style="margin-top:0.5rem;">Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a></p>
        </div>

    </div>
</div>

</body>
</html>

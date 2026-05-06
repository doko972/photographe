<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié — F&A Viking</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&family=Cinzel:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <span class="logo-rune">ᚱ</span>
            <h1>Mot de passe oublié</h1>
            <p>Nous vous enverrons un lien de réinitialisation</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary" style="align-self:stretch; text-align:center;">
                📧 Envoyer le lien
            </button>
        </form>

        <div class="auth-footer">
            <p><a href="{{ route('login') }}">← Retour à la connexion</a></p>
        </div>
    </div>
</div>
</body>
</html>

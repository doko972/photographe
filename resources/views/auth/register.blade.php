<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>S'inscrire — F&A Viking</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&family=Cinzel:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-logo">
            <span class="logo-rune">ᚢ</span>
            <h1>Rejoindre la légende</h1>
            <p>Créez votre compte de guerrier</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name">Votre nom complet</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Thomas Durand" class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="viking_pseudo">Votre surnom viking <span style="color:var(--clr-gold)">*</span></label>
                <input type="text" id="viking_pseudo" name="viking_pseudo" value="{{ old('viking_pseudo') }}" placeholder="Ex : Björn le Valeureux" class="{{ $errors->has('viking_pseudo') ? 'is-invalid' : '' }}">
                @error('viking_pseudo') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required autocomplete="new-password" class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                @error('password') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary" style="align-self:stretch; text-align:center;">
                🛡️ Créer mon compte
            </button>
        </form>

        <div class="auth-footer">
            <p>Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p>
        </div>

    </div>
</div>

</body>
</html>

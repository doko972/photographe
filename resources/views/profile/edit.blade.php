@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="page-header">
    <span class="section-badge">⚔</span>
    <h1>Mon Profil</h1>
    <p>Gérez vos informations de guerrier</p>
</div>

<div class="profile-page">

    {{-- Section 1 : Informations --}}
    <section class="profile-section">
        <h2 class="profile-section__title">Informations personnelles</h2>

        @if (session('status') === 'profile-updated')
            <div class="alert alert--success">Profil mis à jour avec succès.</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="name" class="form-label">Nom</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="form-input @error('name') is-invalid @enderror"
                       required autocomplete="name">
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       class="form-input @error('email') is-invalid @enderror"
                       required autocomplete="email">
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="viking_pseudo" class="form-label">Pseudo Viking <span class="form-label__optional">(optionnel)</span></label>
                <input type="text" id="viking_pseudo" name="viking_pseudo"
                       value="{{ old('viking_pseudo', $user->viking_pseudo) }}"
                       class="form-input @error('viking_pseudo') is-invalid @enderror"
                       maxlength="100" placeholder="Ex: Bjorn le Terrible">
                @error('viking_pseudo')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn--primary">Sauvegarder</button>
            </div>
        </form>
    </section>

    {{-- Section 2 : Mot de passe --}}
    <section class="profile-section">
        <h2 class="profile-section__title">Changer le mot de passe</h2>

        @if (session('status') === 'password-updated')
            <div class="alert alert--success">Mot de passe mis à jour.</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="profile-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="current_password" class="form-label">Mot de passe actuel</label>
                <input type="password" id="current_password" name="current_password"
                       class="form-input @error('current_password', 'updatePassword') is-invalid @enderror"
                       autocomplete="current-password">
                @error('current_password', 'updatePassword')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Nouveau mot de passe</label>
                <input type="password" id="password" name="password"
                       class="form-input @error('password', 'updatePassword') is-invalid @enderror"
                       autocomplete="new-password">
                @error('password', 'updatePassword')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-input"
                       autocomplete="new-password">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn--primary">Modifier le mot de passe</button>
            </div>
        </form>
    </section>

    {{-- Section 3 : Mes photos --}}
    <section class="profile-section">
        <h2 class="profile-section__title">Mes photos</h2>
        <p class="profile-section__desc">
            Vous avez soumis
            <strong>{{ Auth::user()->photos()->count() }}</strong> photo(s)
            au concours.
        </p>
        <a href="{{ route('photos.mine') }}" class="btn btn--secondary">Gérer mes photos</a>
    </section>

    {{-- Section 4 : Danger --}}
    <section class="profile-section profile-section--danger">
        <h2 class="profile-section__title">Zone de danger</h2>
        <p class="profile-section__desc">La suppression de votre compte est définitive et entraîne la perte de toutes vos photos et votes.</p>

        <form method="POST" action="{{ route('profile.destroy') }}" class="profile-form"
              onsubmit="return confirm('Supprimer définitivement votre compte ?')">
            @csrf
            @method('DELETE')

            <div class="form-group">
                <label for="delete_password" class="form-label">Confirmez votre mot de passe</label>
                <input type="password" id="delete_password" name="password"
                       class="form-input @error('password', 'userDeletion') is-invalid @enderror"
                       autocomplete="current-password">
                @error('password', 'userDeletion')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn--danger">Supprimer mon compte</button>
            </div>
        </form>
    </section>

</div>
@endsection

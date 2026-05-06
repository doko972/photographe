<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Florent & Aurélie — 40 ans Viking')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&family=Cinzel:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

    @if(session('success'))
        <span data-flash-success="{{ session('success') }}" hidden></span>
    @endif
    @if(session('error'))
        <span data-flash-error="{{ session('error') }}" hidden></span>
    @endif

    <header class="site-header" id="site-header">
        <div class="header-inner">

            <a href="{{ route('home') }}" class="logo" aria-label="Accueil">
                <span class="logo-rune">ᚠ</span>
                <span class="logo-text">F<span class="logo-amp">&</span>A</span>
            </a>

            <nav class="nav-desktop" aria-label="Navigation principale">
                <ul>
                    <li><a href="{{ route('home') }}" class="nav-link @yield('nav-home')">Accueil</a></li>
                    <li><a href="{{ route('home') }}#anniversaire" class="nav-link">L'Anniversaire</a></li>
                    <li><a href="{{ route('home') }}#ancetres" class="nav-link">Nos Ancêtres</a></li>
                    <li><a href="{{ route('home') }}#concours" class="nav-link">Concours</a></li>
                    <li><a href="{{ route('gallery.index') }}" class="nav-link @yield('nav-gallery')">Galerie</a></li>
                    <li><a href="{{ route('votes.podium') }}" class="nav-link @yield('nav-podium')">Podium</a></li>
                    @auth
                        <li><a href="{{ route('votes.favorites') }}" class="nav-link @yield('nav-favorites')">Mes Favoris</a></li>
                        <li><a href="{{ route('photos.create') }}" class="nav-link nav-cta @yield('nav-poster')">⚔️ Poster</a></li>
                        @if(auth()->user()->is_admin)
                            <li><a href="{{ route('admin.dashboard') }}" class="nav-link">⚙️ Admin</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('login') }}" class="nav-link nav-cta">Se connecter</a></li>
                    @endauth
                </ul>
            </nav>

            <button class="burger" id="burger" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="nav-mobile">
                <span class="burger-bar"></span>
                <span class="burger-bar"></span>
                <span class="burger-bar"></span>
            </button>
        </div>

        <nav class="nav-mobile" id="nav-mobile" aria-label="Navigation mobile" aria-hidden="true">
            <ul>
                <li><a href="{{ route('home') }}" class="nav-link">Accueil</a></li>
                <li><a href="{{ route('home') }}#anniversaire" class="nav-link">L'Anniversaire</a></li>
                <li><a href="{{ route('home') }}#ancetres" class="nav-link">Nos Ancêtres</a></li>
                <li><a href="{{ route('home') }}#concours" class="nav-link">Concours</a></li>
                <li><a href="{{ route('gallery.index') }}" class="nav-link">Galerie</a></li>
                <li><a href="{{ route('votes.podium') }}" class="nav-link">Podium</a></li>
                @auth
                    <li><a href="{{ route('votes.favorites') }}" class="nav-link">Mes Favoris</a></li>
                    <li><a href="{{ route('photos.create') }}" class="nav-link nav-cta">⚔️ Poster ma photo</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="nav-link">Mon Profil</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link" style="width:100%;text-align:left;">Se déconnecter</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="nav-link">Se connecter</a></li>
                    <li><a href="{{ route('register') }}" class="nav-link nav-cta">S'inscrire</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer" aria-label="Pied de page">
        <div class="footer-inner">
            <div class="footer-brand">
                <span class="logo-rune">ᚠ</span>
                <span class="logo-text">F<span class="logo-amp">&</span>A</span>
                <p class="footer-tagline">Quarante hivers • Deux légendes</p>
            </div>
            <nav class="footer-nav" aria-label="Navigation pied de page">
                <h4 class="footer-nav-title">Navigation</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Accueil</a></li>
                    <li><a href="{{ route('gallery.index') }}">La Galerie</a></li>
                    <li><a href="{{ route('votes.podium') }}">Le Podium</a></li>
                    @auth
                        <li><a href="{{ route('votes.favorites') }}">Mes Favoris</a></li>
                        <li><a href="{{ route('photos.create') }}">Poster une photo</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Se connecter</a></li>
                    @endauth
                </ul>
            </nav>
            <div class="footer-info">
                <h4 class="footer-nav-title">L'Événement</h4>
                <p>Date : Weekend du 16 mai 2026</p>
                <p>Lieu : Voir le Msg What'sApp</p>
                <p>Festin viking au programme !</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© {{ date('Y') }} Florent & Aurélie · Tous droits réservés</p>
            <p>Site réalisé par <a href="https://ateliernormandduweb.fr/" target="_blank" rel="noopener noreferrer">Atelier Normand du Web</a></p>
        </div>
    </footer>

    <div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Agrandissement de la photo" aria-hidden="true">
        <div class="lightbox-inner">
            <button class="lightbox-close" id="lightbox-close" aria-label="Fermer">✕</button>
            <div id="lightbox-body"></div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

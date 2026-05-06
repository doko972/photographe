@extends('layouts.app')

@section('title', 'Florent & Aurélie — 40 ans Viking')
@section('nav-home', 'active')

@section('content')

    {{-- ===== HERO + CAROUSEL ===== --}}
    <section class="hero" aria-label="Présentation de l'événement">

        <div class="carousel" id="carousel" aria-roledescription="carousel" aria-label="Images de l'événement">
            <div class="carousel-track" id="carousel-track">

                <div class="carousel-slide" role="group" aria-roledescription="slide" aria-label="Diapositive 1 sur 3">
                    <div class="placeholder-img">
                        <span class="placeholder-rune">ᚢ</span>
                        <span class="placeholder-label">Florent & Aurélie — 40 ans</span>
                    </div>
                </div>

                <div class="carousel-slide" role="group" aria-roledescription="slide" aria-label="Diapositive 2 sur 3">
                    <div class="placeholder-img">
                        <span class="placeholder-rune">ᚦ</span>
                        <span class="placeholder-label">Soirée Viking • 2025</span>
                    </div>
                </div>

                <div class="carousel-slide" role="group" aria-roledescription="slide" aria-label="Diapositive 3 sur 3">
                    <div class="placeholder-img">
                        <span class="placeholder-rune">ᚨ</span>
                        <span class="placeholder-label">Costumes • Festin • Légendes</span>
                    </div>
                </div>

            </div>
            <button class="carousel-btn carousel-prev" id="carousel-prev" aria-label="Diapositive précédente">&#10094;</button>
            <button class="carousel-btn carousel-next" id="carousel-next" aria-label="Diapositive suivante">&#10095;</button>
            <div class="carousel-dots" id="carousel-dots" aria-label="Indicateurs">
                <button class="dot active" data-index="0" aria-label="Diapositive 1"></button>
                <button class="dot" data-index="1" aria-label="Diapositive 2"></button>
                <button class="dot" data-index="2" aria-label="Diapositive 3"></button>
            </div>
        </div>

        <div class="hero-content">
            <p class="hero-eyebrow">Une soirée hors du temps</p>
            <h1 class="hero-title">Florent <span class="hero-amp">&</span> Aurélie</h1>
            <p class="hero-subtitle">Fêtent leurs <strong>40 printemps</strong> sous la bannière des Vikings</p>
            <div class="hero-actions">
                @auth
                    <a href="{{ route('photos.create') }}" class="btn btn-primary">Poster ma photo</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary">Rejoindre la fête</a>
                @endauth
                <a href="{{ route('gallery.index') }}" class="btn btn-secondary">Voir la galerie</a>
            </div>
        </div>

    </section>

    {{-- ===== ANNIVERSAIRE ===== --}}
    <section class="section section-anniv" id="anniversaire" aria-labelledby="anniv-title">
        <div class="section-inner">
            <div class="section-badge">ᚠ</div>
            <h2 class="section-title" id="anniv-title">L'Anniversaire</h2>
            <p class="section-subtitle">Deux guerriers, un même festin</p>
            <div class="section-content two-cols">
                <div class="card card-person animate-fade-up">
                    <div class="card-avatar placeholder-avatar">F</div>
                    <h3>Florent</h3>
                    <p>Quarante hivers à parcourir les fjords de la vie, toujours prêt pour la prochaine aventure.</p>
                </div>
                <div class="card card-person animate-fade-up">
                    <div class="card-avatar placeholder-avatar">A</div>
                    <h3>Aurélie</h3>
                    <p>Valkyrie au cœur vaillant, quarante saisons à illuminer le chemin de ceux qui l'entourent.</p>
                </div>
            </div>
            <p class="section-desc">
                Rejoignez-nous pour ce festin mémorable, costumés en dignes descendants des Normands.
                Une nuit de légendes, de rires et de souvenirs gravés comme des runes.
            </p>
        </div>
    </section>

    {{-- ===== NOS ANCÊTRES ===== --}}
    <section class="section section-ancetres" id="ancetres" aria-labelledby="ancetres-title"
             style="--drakkar-bg: url('{{ asset('images/drakkar.webp') }}')">
        <div class="section-inner">
            <div class="section-badge">ᚢ</div>
            <h2 class="section-title" id="ancetres-title">Nos Ancêtres</h2>
            <p class="section-subtitle">Les Vikings & les Normands — Qui étaient-ils vraiment ?</p>
            <div class="section-content facts-grid">
                <article class="fact-card fact-card--warriors animate-fade-up"
                         style="--card-bg: url('{{ asset('images/warrior.webp') }}')">
                    <div class="fact-content">
                        <h3>Des guerriers… et des marchands</h3>
                        <p>Les Vikings n'étaient pas que des pillards. Explorateurs, commerçants et bâtisseurs, ils ont façonné l'Europe médiévale.</p>
                    </div>
                </article>
                <article class="fact-card fact-card--helmets animate-fade-up"
                         style="--card-bg: url('{{ asset('images/horns.webp') }}')">
                    <div class="fact-content">
                        <h3>Pas de cornes sur les casques !</h3>
                        <p>Contrairement au mythe populaire, les casques vikings étaient simples, souvent en cuir ou en métal, sans ornement cornu.</p>
                    </div>
                </article>
                <article class="fact-card fact-card--normands animate-fade-up"
                         style="--card-bg: url('{{ asset('images/normand.webp') }}')">
                    <div class="fact-content">
                        <h3>Les Normands en Normandie</h3>
                        <p>Installés en France au Xe siècle, les "hommes du Nord" ont donné leur nom à la Normandie et conquis l'Angleterre en 1066.</p>
                    </div>
                </article>
                <article class="fact-card fact-card--runes animate-fade-up"
                         style="--card-bg: url('{{ asset('images/runes.webp') }}')">
                    <div class="fact-content">
                        <h3>L'écriture runique</h3>
                        <p>Les runes étaient l'alphabet des peuples germaniques nordiques, gravées sur pierres, armes et bijoux.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- ===== CONCOURS ===== --}}
    <section class="section section-concours" id="concours" aria-labelledby="concours-title">
        <div class="section-inner">
            <div class="section-badge">ᚦ</div>
            <h2 class="section-title" id="concours-title">Le Concours de Costumes</h2>
            <p class="section-subtitle">Que le meilleur Viking l'emporte !</p>
            <div class="section-content steps-list">
                <div class="step animate-fade-up">
                    <div class="step-number">1</div>
                    <div class="step-body">
                        <h3>Préparez votre costume</h3>
                        <p>Enfilez votre plus bel habit viking ou normand — tunique, fourrure, épée en mousse… soyez créatifs !</p>
                    </div>
                </div>
                <div class="step animate-fade-up">
                    <div class="step-number">2</div>
                    <div class="step-body">
                        <h3>Prenez-vous en photo</h3>
                        <p>Seul, en duo ou en groupe. Le photomaton viking vous attend sur place !</p>
                    </div>
                </div>
                <div class="step animate-fade-up">
                    <div class="step-number">3</div>
                    <div class="step-body">
                        <h3>Postez votre photo</h3>
                        <p>Connectez-vous et uploadez votre meilleur cliché sur le site.</p>
                    </div>
                </div>
                <div class="step animate-fade-up">
                    <div class="step-number">4</div>
                    <div class="step-body">
                        <h3>Votez pour votre favori !</h3>
                        <p>Ajoutez des photos à vos favoris, puis classez vos 10 meilleures de 1 à 10.</p>
                    </div>
                </div>
            </div>
            @auth
                <a href="{{ route('photos.create') }}" class="btn btn-primary btn-center">⚔️ Je participe !</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-center">⚔️ S'inscrire pour participer</a>
            @endauth
        </div>
    </section>

@endsection

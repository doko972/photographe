@extends('layouts.app')

@section('title', 'Galerie des Vikings — F&A')
@section('nav-gallery', 'active')

@section('content')

    <div class="page-header">
        <div class="section-badge">ᚷ</div>
        <h1>La Galerie des Vikings</h1>
        <p>Cliquez sur ❤️ pour ajouter une photo à vos favoris</p>
    </div>

    <div class="gallery-controls">
        <div class="gallery-sort" role="group" aria-label="Trier les photos">
            <button class="sort-btn {{ $sort === 'recent' ? 'active' : '' }}" data-sort="recent" aria-pressed="{{ $sort === 'recent' ? 'true' : 'false' }}">🕐 Plus récentes</button>
            <button class="sort-btn {{ $sort === 'popular' ? 'active' : '' }}" data-sort="popular" aria-pressed="{{ $sort === 'popular' ? 'true' : 'false' }}">🔥 Plus populaires</button>
        </div>
        <p class="gallery-count" aria-live="polite">{{ $photos->total() }} photo{{ $photos->total() > 1 ? 's' : '' }}</p>
    </div>

    <div class="gallery-grid" id="gallery-grid" aria-label="Galerie des photos de costumes">

        @forelse($photos as $photo)
            <div class="gallery-card">
                <div class="gallery-card-img" onclick="openLightbox('{{ Storage::url($photo->thumbnail_path) }}', '{{ e($photo->viking_pseudo) }}')" role="button" tabindex="0" aria-label="Agrandir la photo de {{ $photo->viking_pseudo }}">
                    <img src="{{ Storage::url($photo->thumbnail_path) }}" alt="Costume de {{ $photo->viking_pseudo }}" loading="lazy">
                    <a href="{{ route('gallery.download', $photo) }}" class="gallery-dl-btn" onclick="event.stopPropagation()" aria-label="Télécharger en HD">⬇ Télécharger</a>
                </div>
                <div class="gallery-card-body">
                    <div class="gallery-card-info">
                        <h3>{{ $photo->viking_pseudo }}</h3>
                        <p>{{ $photo->created_at->format('d/m/Y') }}</p>
                    </div>
                    @auth
                        <button
                            class="heart-btn {{ in_array($photo->id, $likedIds) ? 'liked' : '' }}"
                            data-photo-id="{{ $photo->id }}"
                            aria-label="{{ in_array($photo->id, $likedIds) ? 'Retirer des favoris' : 'Ajouter aux favoris' }}"
                        >
                            <span class="heart-icon">{{ in_array($photo->id, $likedIds) ? '❤️' : '🤍' }}</span>
                            <span class="heart-count">{{ $photo->likes_count }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="heart-btn" title="Connectez-vous pour liker">
                            <span class="heart-icon">🤍</span>
                            <span class="heart-count">{{ $photo->likes_count }}</span>
                        </a>
                    @endauth
                </div>
            </div>
        @empty
            <p class="gallery-empty">Aucune photo n'a encore été publiée. Soyez le premier guerrier !</p>
        @endforelse

    </div>

    <div class="pagination-wrap">
        {{ $photos->links() }}
    </div>

    @guest
        <div style="text-align:center; padding: 0 1rem 4rem;">
            <p style="color:var(--clr-grey-lt); margin-bottom:1rem; font-family:var(--clr-display); font-size:0.9rem;">
                Vous n'avez pas encore de costume dans la galerie ?
            </p>
            <a href="{{ route('register') }}" class="btn btn-primary">⚔️ S'inscrire pour participer</a>
        </div>
    @endguest

@endsection

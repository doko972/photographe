@extends('layouts.app')

@section('title', 'Podium Viking — F&A')
@section('nav-podium', 'active')

@section('content')

    <div class="page-header">
        <div class="section-badge">🏆</div>
        <h1>Le Podium Viking</h1>
        <p>Les costumes élus par la communauté</p>
    </div>

    @if($photos->isEmpty())
        <div style="text-align:center; padding: 4rem 1rem;">
            <p style="color:var(--clr-grey-lt); font-family:var(--font-display);">Aucun vote n'a encore été enregistré.</p>
            <a href="{{ route('gallery.index') }}" class="btn btn-primary" style="margin-top:1.5rem; display:inline-block;">Voir la galerie</a>
        </div>
    @else

        {{-- TOP 3 --}}
        @if($photos->count() >= 3)
        <div class="podium-section">
            <div class="podium-top3">

                {{-- 2ème place --}}
                <div class="podium-place podium-place--2">
                    <span class="podium-medal">🥈</span>
                    <div class="podium-photo">
                        <img src="{{ Storage::url($photos[1]->thumbnail_path) }}" alt="{{ $photos[1]->viking_pseudo }}">
                    </div>
                    <p class="podium-name">{{ $photos[1]->viking_pseudo }}</p>
                    <p class="podium-score">{{ $photos[1]->vote_score }} pts</p>
                    <div class="podium-stand"><span>2</span></div>
                </div>

                {{-- 1ère place --}}
                <div class="podium-place podium-place--1">
                    <span class="podium-medal">🥇</span>
                    <div class="podium-photo">
                        <img src="{{ Storage::url($photos[0]->thumbnail_path) }}" alt="{{ $photos[0]->viking_pseudo }}">
                    </div>
                    <p class="podium-name">{{ $photos[0]->viking_pseudo }}</p>
                    <p class="podium-score">{{ $photos[0]->vote_score }} pts</p>
                    <div class="podium-stand"><span>1</span></div>
                </div>

                {{-- 3ème place --}}
                <div class="podium-place podium-place--3">
                    <span class="podium-medal">🥉</span>
                    <div class="podium-photo">
                        <img src="{{ Storage::url($photos[2]->thumbnail_path) }}" alt="{{ $photos[2]->viking_pseudo }}">
                    </div>
                    <p class="podium-name">{{ $photos[2]->viking_pseudo }}</p>
                    <p class="podium-score">{{ $photos[2]->vote_score }} pts</p>
                    <div class="podium-stand"><span>3</span></div>
                </div>

            </div>
        </div>
        @endif

        {{-- Classement complet --}}
        <div class="podium-section">
            <h2 class="section-title" style="margin-bottom: 1.5rem;">Classement complet</h2>
            <div class="ranking-list">
                @foreach($photos as $index => $photo)
                    <div class="ranking-item">
                        <span class="ranking-pos">
                            @if($index === 0) 🥇
                            @elseif($index === 1) 🥈
                            @elseif($index === 2) 🥉
                            @else {{ $index + 1 }}
                            @endif
                        </span>
                        <img class="ranking-thumb" src="{{ Storage::url($photo->thumbnail_path) }}" alt="{{ $photo->viking_pseudo }}">
                        <div class="ranking-info">
                            <h3>{{ $photo->viking_pseudo }}</h3>
                            <p>{{ $photo->votes->count() }} vote{{ $photo->votes->count() > 1 ? 's' : '' }} · {{ $photo->likes_count }} ❤️</p>
                        </div>
                        <div class="ranking-score">{{ $photo->vote_score }} pts</div>
                    </div>
                @endforeach
            </div>
        </div>

    @endif

@endsection

@extends('layouts.app')

@section('title', 'Mes Favoris — F&A Viking')
@section('nav-favorites', 'active')

@section('content')

    <div class="page-header">
        <div class="section-badge">❤️</div>
        <h1>Mes Favoris</h1>
        <p>Classez vos 10 photos préférées de 1 (meilleure) à 10</p>
    </div>

    @if($likedPhotos->isEmpty())
        <div style="text-align:center; padding: 4rem 1rem;">
            <p style="color:var(--clr-grey-lt); font-family:var(--font-display); font-size:1rem; margin-bottom:1.5rem;">
                Vous n'avez pas encore de favoris. Parcourez la galerie et cliquez sur ❤️ !
            </p>
            <a href="{{ route('gallery.index') }}" class="btn btn-primary">Voir la galerie</a>
        </div>
    @else

        <form id="votes-form" method="POST" action="{{ route('votes.store') }}">
            @csrf

            @if($errors->any())
                <div class="alert alert-error" style="max-width:1100px; margin:1rem auto; padding:0 1rem;">
                    {{ $errors->first('votes') }}
                </div>
            @endif

            <div class="favorites-grid">
                @foreach($likedPhotos as $photo)
                    <div class="fav-card {{ isset($userVotes[$photo->id]) ? 'voted' : '' }}">
                        <div class="fav-card-img">
                            <img src="{{ Storage::url($photo->thumbnail_path) }}" alt="Costume de {{ $photo->viking_pseudo }}" loading="lazy">
                        </div>
                        <div class="fav-card-body">
                            <p class="fav-card-name">{{ $photo->viking_pseudo }}</p>
                            <div class="rank-selector">
                                <label for="rank-{{ $photo->id }}">Rang</label>
                                <select
                                    id="rank-{{ $photo->id }}"
                                    class="rank-select {{ isset($userVotes[$photo->id]) ? 'rank-assigned' : '' }}"
                                    data-photo-id="{{ $photo->id }}"
                                    name="votes[{{ $loop->index }}][rank]"
                                >
                                    <option value="">—</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ (isset($userVotes[$photo->id]) && $userVotes[$photo->id] == $i) ? 'selected' : '' }}>
                                            {{ $i }}{{ $i === 1 ? ' 🥇' : ($i === 2 ? ' 🥈' : ($i === 3 ? ' 🥉' : '')) }}
                                        </option>
                                    @endfor
                                </select>
                                <input type="hidden" name="votes[{{ $loop->index }}][photo_id]" value="{{ $photo->id }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="votes-save-bar">
                <button type="submit" class="btn btn-primary">
                    🛡️ Sauvegarder mes votes
                </button>
            </div>

        </form>

    @endif

@endsection

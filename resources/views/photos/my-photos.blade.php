@extends('layouts.app')

@section('title', 'Mes Photos')

@section('content')
<div class="page-header">
    <span class="section-badge">ᚠ</span>
    <h1>Mes Photos</h1>
    <p>Gérez vos soumissions au concours</p>
</div>

<div class="my-photos-page">

    <div class="my-photos-actions">
        <a href="{{ route('photos.create') }}" class="btn btn--primary">+ Ajouter une photo</a>
        <a href="{{ route('profile.edit') }}" class="btn btn--ghost">← Retour au profil</a>
    </div>

    @if ($photos->isEmpty())
        <div class="my-photos-empty">
            <span class="my-photos-empty__icon">🛡</span>
            <p>Vous n'avez pas encore soumis de photo.</p>
            <a href="{{ route('photos.create') }}" class="btn btn--primary">Soumettre ma première photo</a>
        </div>
    @else
        <div class="my-photos-grid">
            @foreach ($photos as $photo)
                <article class="my-photo-card">
                    <div class="my-photo-card__thumb">
                        <img src="{{ Storage::url($photo->thumbnail_path) }}"
                             alt="Photo de {{ $photo->viking_pseudo }}">
                        <span class="my-photo-card__status my-photo-card__status--{{ $photo->status }}">
                            @switch($photo->status)
                                @case('approved')  Approuvée  @break
                                @case('rejected')  Rejetée    @break
                                @default           En attente
                            @endswitch
                        </span>
                    </div>
                    <div class="my-photo-card__body">
                        <p class="my-photo-card__pseudo">{{ $photo->viking_pseudo }}</p>
                        @if ($photo->caption)
                            <p class="my-photo-card__caption">{{ Str::limit($photo->caption, 80) }}</p>
                        @endif
                        <div class="my-photo-card__meta">
                            <span>♥ {{ $photo->likes_count }}</span>
                            <span>{{ $photo->created_at->diffForHumans() }}</span>
                        </div>
                        <form method="POST" action="{{ route('photos.destroy', $photo) }}"
                              onsubmit="return confirm('Supprimer cette photo définitivement ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn--danger btn--sm">Supprimer</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $photos->links() }}
        </div>
    @endif

</div>
@endsection

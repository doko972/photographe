@extends('layouts.app')

@section('title', 'Poster ma photo — F&A Viking')
@section('nav-poster', 'active')

@section('content')

    <div class="page-header">
        <div class="section-badge">ᚨ</div>
        <h1>Poster ma photo</h1>
        <p>Immortalisez votre costume viking et entrez dans la légende !</p>
    </div>

    <section class="upload-section" aria-label="Formulaire de publication de photo">

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="padding-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('photos.store') }}" enctype="multipart/form-data" id="upload-form" novalidate>
            @csrf

            <div class="upload-zone" id="upload-zone" role="button" tabindex="0" aria-label="Zone de dépôt de photo — cliquer ou glisser une image">
                <input type="file" id="file-input" name="photo" accept="image/*" aria-label="Choisir une photo" required>
                <span class="upload-icon" aria-hidden="true">📸</span>
                <h3>Glissez votre photo ici</h3>
                <p>ou cliquez pour sélectionner un fichier</p>
                <p style="margin-top:0.5rem; font-size:0.78rem; color:var(--clr-grey);">JPG, PNG, WEBP — max 10 Mo</p>
            </div>

            <div class="upload-preview" id="upload-preview" aria-label="Aperçu de la photo sélectionnée">
                <img id="preview-img" src="" alt="Aperçu de votre photo">
                <button type="button" class="preview-remove" id="preview-remove" aria-label="Supprimer la photo sélectionnée">✕</button>
            </div>

            <div class="upload-form">
                <div class="form-group">
                    <label for="viking-pseudo">Votre surnom viking <span style="color:var(--clr-gold)">*</span></label>
                    <input type="text" id="viking-pseudo" name="viking_pseudo" value="{{ old('viking_pseudo', auth()->user()->viking_pseudo) }}" placeholder="Ex : Björn le Valeureux" required class="{{ $errors->has('viking_pseudo') ? 'is-invalid' : '' }}">
                    @error('viking_pseudo') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="caption">Une légende pour la photo</label>
                    <textarea id="caption" name="caption" placeholder="Décrivez votre costume ou votre exploit du soir…">{{ old('caption') }}</textarea>
                    @error('caption') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="align-self:center; margin-top:0.5rem;">
                    ⚔️ Publier dans la galerie
                </button>
            </div>

        </form>

        <div class="alert alert-info" style="margin-top: 1.5rem;">
            🔒 <strong>Note :</strong> Votre photo sera visible après validation par les organisateurs.
        </div>

    </section>

@endsection

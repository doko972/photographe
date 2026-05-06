@extends('layouts.app')

@section('title', 'Admin — Tableau de bord')

@section('content')
<div class="admin-layout">

    <aside class="admin-sidebar">
        <h3>⚙️ Administration</h3>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="active">📊 Tableau de bord</a>
            <a href="{{ route('admin.photos') }}">🖼️ Photos</a>
            <a href="{{ route('admin.photos', ['status' => 'approved']) }}">✅ Approuvées</a>
            <a href="{{ route('admin.photos', ['status' => 'rejected']) }}">❌ Rejetées</a>
            <a href="{{ route('admin.users') }}">👥 Utilisateurs</a>
            <a href="{{ route('votes.podium') }}">🏆 Podium</a>
        </nav>
    </aside>

    <div class="admin-main">
        <h2 class="admin-title">Tableau de bord</h2>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card__value">{{ $stats['users'] }}</div>
                <div class="stat-card__label">Utilisateurs</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__value">{{ $stats['photos'] }}</div>
                <div class="stat-card__label">Photos totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__value" style="color:var(--clr-gold)">{{ $stats['pending'] }}</div>
                <div class="stat-card__label">En attente</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__value" style="color:#5cd68a">{{ $stats['approved'] }}</div>
                <div class="stat-card__label">Approuvées</div>
            </div>
        </div>

        @if($pendingPhotos->isNotEmpty())
        <h3 style="font-family:var(--font-display); color:var(--clr-gold); margin-bottom:1rem; font-size:1rem; letter-spacing:0.08em; text-transform:uppercase;">
            Photos en attente de validation
        </h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Surnom viking</th>
                    <th>Auteur</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingPhotos as $photo)
                <tr>
                    <td><img class="admin-thumb" src="{{ Storage::url($photo->thumbnail_path) }}" alt="{{ $photo->viking_pseudo }}"></td>
                    <td>{{ $photo->viking_pseudo }}</td>
                    <td>{{ $photo->user->name }}</td>
                    <td>{{ $photo->created_at->format('d/m H:i') }}</td>
                    <td>
                        <div class="action-group">
                            <form method="POST" action="{{ route('admin.photos.approve', $photo) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-primary">✅ Approuver</button>
                            </form>
                            <form method="POST" action="{{ route('admin.photos.reject', $photo) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-danger">❌ Rejeter</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="alert alert-success">✅ Aucune photo en attente de validation.</div>
        @endif
    </div>

</div>
@endsection

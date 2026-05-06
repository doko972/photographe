@extends('layouts.app')

@section('title', 'Admin — Photos')

@section('content')
<div class="admin-layout">

    <aside class="admin-sidebar">
        <h3>⚙️ Administration</h3>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}">📊 Tableau de bord</a>
            <a href="{{ route('admin.photos') }}" class="{{ $status === 'pending' ? 'active' : '' }}">🖼️ Photos en attente</a>
            <a href="{{ route('admin.photos', ['status' => 'approved']) }}" class="{{ $status === 'approved' ? 'active' : '' }}">✅ Approuvées</a>
            <a href="{{ route('admin.photos', ['status' => 'rejected']) }}" class="{{ $status === 'rejected' ? 'active' : '' }}">❌ Rejetées</a>
            <a href="{{ route('admin.users') }}">👥 Utilisateurs</a>
            <a href="{{ route('votes.podium') }}">🏆 Podium</a>
        </nav>
    </aside>

    <div class="admin-main">
        <h2 class="admin-title">
            Photos — <span class="badge badge-{{ $status }}">{{ ucfirst($status) }}</span>
        </h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($photos->isEmpty())
            <div class="alert alert-info">Aucune photo dans cette catégorie.</div>
        @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Surnom viking</th>
                    <th>Auteur</th>
                    <th>Légende</th>
                    <th>❤️</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($photos as $photo)
                <tr>
                    <td><img class="admin-thumb" src="{{ Storage::url($photo->thumbnail_path) }}" alt="{{ $photo->viking_pseudo }}"></td>
                    <td>{{ $photo->viking_pseudo }}</td>
                    <td>{{ $photo->user->name }}</td>
                    <td style="max-width:200px; color:var(--clr-grey); font-size:0.8rem;">{{ Str::limit($photo->caption, 60) }}</td>
                    <td>{{ $photo->likes_count }}</td>
                    <td>{{ $photo->created_at->format('d/m H:i') }}</td>
                    <td>
                        <div class="action-group">
                            @if($status !== 'approved')
                            <form method="POST" action="{{ route('admin.photos.approve', $photo) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-primary">✅</button>
                            </form>
                            @endif
                            @if($status !== 'rejected')
                            <form method="POST" action="{{ route('admin.photos.reject', $photo) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-secondary">❌</button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.photos.destroy', $photo) }}" onsubmit="return confirm('Supprimer définitivement cette photo ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:1rem;">{{ $photos->links() }}</div>
        @endif
    </div>

</div>
@endsection

@extends('layouts.app')

@section('title', 'Admin — Utilisateurs')

@section('content')
<div class="admin-layout">

    <aside class="admin-sidebar">
        <h3>⚙️ Administration</h3>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}">📊 Tableau de bord</a>
            <a href="{{ route('admin.photos') }}">🖼️ Photos</a>
            <a href="{{ route('admin.users') }}" class="active">👥 Utilisateurs</a>
            <a href="{{ route('votes.podium') }}">🏆 Podium</a>
        </nav>
    </aside>

    <div class="admin-main">
        <h2 class="admin-title">Utilisateurs</h2>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Surnom Viking</th>
                    <th>Email</th>
                    <th>Photos</th>
                    <th>Favoris</th>
                    <th>Votes</th>
                    <th>Rôle</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td style="color:var(--clr-gold)">{{ $user->viking_pseudo ?: '—' }}</td>
                    <td style="font-size:0.8rem; color:var(--clr-grey)">{{ $user->email }}</td>
                    <td>{{ $user->photos_count }}</td>
                    <td>{{ $user->likes_count }}</td>
                    <td>{{ $user->votes_count }}</td>
                    <td>
                        @if($user->is_admin)
                            <span class="badge badge-approved">Admin</span>
                        @else
                            <span class="badge badge-pending">Invité</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:1rem;">{{ $users->links() }}</div>
    </div>

</div>
@endsection

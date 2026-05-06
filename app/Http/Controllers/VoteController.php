<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VoteController extends Controller
{
    public function favorites(): View
    {
        $user = Auth::user();

        $likedPhotos = $user->likedPhotos()
            ->where('status', 'approved')
            ->withCount('likes')
            ->get();

        $userVotes = $user->votes()->pluck('rank', 'photo_id')->toArray();

        return view('votes.favorites', compact('likedPhotos', 'userVotes'));
    }

    public function podium(): View
    {
        $photos = Photo::where('status', 'approved')
            ->withCount('likes')
            ->with('votes', 'user')
            ->get()
            ->map(function ($photo) {
                $photo->vote_score = $photo->votes->sum(fn($v) => 11 - $v->rank);
                return $photo;
            })
            ->sortByDesc('vote_score')
            ->values();

        return view('votes.podium', compact('photos'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'votes' => 'required|array|min:1|max:10',
            'votes.*.photo_id' => 'required|exists:photos,id',
            'votes.*.rank'     => 'required|integer|min:1|max:10',
        ]);

        $user = Auth::user();
        $submissions = collect($request->votes);

        $photoIds = $submissions->pluck('photo_id');
        $ranks    = $submissions->pluck('rank');

        if ($photoIds->unique()->count() !== $photoIds->count()) {
            return back()->withErrors(['votes' => 'Chaque photo ne peut recevoir qu\'un seul rang.']);
        }
        if ($ranks->unique()->count() !== $ranks->count()) {
            return back()->withErrors(['votes' => 'Chaque rang ne peut être attribué qu\'une seule fois.']);
        }

        foreach ($submissions as $item) {
            Vote::updateOrCreate(
                ['user_id' => $user->id, 'photo_id' => $item['photo_id']],
                ['rank' => $item['rank']]
            );
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('votes.favorites')
            ->with('success', 'Vos votes ont été enregistrés !');
    }

    public function destroyVote(Photo $photo): JsonResponse
    {
        Auth::user()->votes()->where('photo_id', $photo->id)->delete();
        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $sort = $request->get('sort', 'recent');

        $query = Photo::where('status', 'approved')
            ->with('user')
            ->withCount('likes');

        if ($sort === 'popular') {
            $query->orderByDesc('likes_count');
        } else {
            $query->latest();
        }

        $photos = $query->paginate(16)->withQueryString();

        $likedIds = Auth::check()
            ? Auth::user()->likes()->pluck('photo_id')->toArray()
            : [];

        return view('gallery.index', compact('photos', 'sort', 'likedIds'));
    }

    public function download(Photo $photo)
    {
        abort_if($photo->status !== 'approved', 403);

        return response()->download(
            storage_path('app/public/' . $photo->original_path),
            'viking_' . $photo->viking_pseudo . '.jpg'
        );
    }
}

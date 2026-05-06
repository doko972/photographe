<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'users'    => User::count(),
            'photos'   => Photo::count(),
            'pending'  => Photo::where('status', 'pending')->count(),
            'approved' => Photo::where('status', 'approved')->count(),
        ];

        $pendingPhotos = Photo::where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingPhotos'));
    }

    public function photos(Request $request): View
    {
        $status = $request->get('status', 'pending');
        $photos = Photo::where('status', $status)
            ->with('user')
            ->withCount('likes')
            ->latest()
            ->paginate(20);

        return view('admin.photos', compact('photos', 'status'));
    }

    public function approve(Photo $photo): RedirectResponse
    {
        $photo->update(['status' => 'approved']);
        return back()->with('success', "Photo de {$photo->viking_pseudo} approuvée.");
    }

    public function reject(Photo $photo): RedirectResponse
    {
        $photo->update(['status' => 'rejected']);
        return back()->with('success', "Photo de {$photo->viking_pseudo} rejetée.");
    }

    public function destroy(Photo $photo): RedirectResponse
    {
        Storage::disk('public')->delete([$photo->original_path, $photo->thumbnail_path]);
        $photo->delete();
        return back()->with('success', 'Photo supprimée définitivement.');
    }

    public function users(): View
    {
        $users = User::withCount(['photos', 'likes', 'votes'])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }
}

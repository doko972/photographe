<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Laravel\Facades\Image;

class PhotoController extends Controller
{
    public function myPhotos(): View
    {
        $photos = Auth::user()->photos()
            ->withCount('likes')
            ->latest()
            ->paginate(12);

        return view('photos.my-photos', compact('photos'));
    }

    public function create(): View
    {
        return view('photos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'photo'         => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
            'viking_pseudo' => 'required|string|max:100',
            'caption'       => 'nullable|string|max:500',
        ]);

        $file = $request->file('photo');
        $filename = uniqid('photo_', true);

        $originalPath = $file->storeAs('photos/originals', $filename . '.' . $file->getClientOriginalExtension(), 'public');

        $thumbnail = Image::decode($file->getRealPath())
            ->scaleDown(width: 600)
            ->encode(new \Intervention\Image\Encoders\JpegEncoder(quality: 70));

        $thumbPath = 'photos/thumbnails/' . $filename . '_thumb.jpg';
        Storage::disk('public')->put($thumbPath, $thumbnail->toString());

        Photo::create([
            'user_id'        => Auth::id(),
            'original_path'  => $originalPath,
            'thumbnail_path' => $thumbPath,
            'viking_pseudo'  => $request->viking_pseudo,
            'caption'        => $request->caption,
            'status'         => 'pending',
        ]);

        return redirect()->route('gallery.index')
            ->with('success', 'Votre photo a été soumise et sera visible après validation.');
    }

    public function destroy(Photo $photo): RedirectResponse
    {
        $this->authorize('delete', $photo);

        Storage::disk('public')->delete([$photo->original_path, $photo->thumbnail_path]);
        $photo->delete();

        return back()->with('success', 'Photo supprimée.');
    }
}

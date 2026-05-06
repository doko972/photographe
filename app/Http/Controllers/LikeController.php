<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Photo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Photo $photo): JsonResponse
    {
        abort_if($photo->status !== 'approved', 403);

        $user = Auth::user();
        $existing = Like::where('user_id', $user->id)->where('photo_id', $photo->id)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            Like::create(['user_id' => $user->id, 'photo_id' => $photo->id]);
            $liked = true;
        }

        $count = $photo->likes()->count();

        return response()->json(['liked' => $liked, 'count' => $count]);
    }
}

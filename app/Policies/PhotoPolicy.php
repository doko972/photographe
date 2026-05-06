<?php

namespace App\Policies;

use App\Models\Photo;
use App\Models\User;

class PhotoPolicy
{
    public function delete(User $user, Photo $photo): bool
    {
        return $user->is_admin || $user->id === $photo->user_id;
    }
}

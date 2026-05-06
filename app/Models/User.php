<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'viking_pseudo',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
        ];
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function likedPhotos()
    {
        return $this->belongsToMany(Photo::class, 'likes');
    }

    public function hasLiked(Photo $photo): bool
    {
        return $this->likes()->where('photo_id', $photo->id)->exists();
    }

    public function hasVotedFor(Photo $photo): bool
    {
        return $this->votes()->where('photo_id', $photo->id)->exists();
    }
}

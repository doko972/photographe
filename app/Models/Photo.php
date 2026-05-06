<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_path',
        'thumbnail_path',
        'viking_pseudo',
        'caption',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    public function getVoteScoreAttribute(): int
    {
        return $this->votes()->sum(\DB::raw('11 - `rank`'));
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}

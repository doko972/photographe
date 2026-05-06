<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\Photo;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeVoteTest extends TestCase
{
    use RefreshDatabase;

    private function approvedPhoto(User $owner): Photo
    {
        return Photo::create([
            'user_id'        => $owner->id,
            'original_path'  => 'photos/originals/x.jpg',
            'thumbnail_path' => 'photos/thumbnails/x_thumb.jpg',
            'viking_pseudo'  => $owner->viking_pseudo ?? 'Viking',
            'status'         => 'approved',
        ]);
    }

    // ── Likes ─────────────────────────────────────────────────

    public function test_guest_cannot_like_photo(): void
    {
        $owner = User::factory()->create();
        $photo = $this->approvedPhoto($owner);

        $this->postJson(route('likes.toggle', $photo))
            ->assertUnauthorized();
    }

    public function test_user_can_like_photo(): void
    {
        $user  = User::factory()->create();
        $owner = User::factory()->create();
        $photo = $this->approvedPhoto($owner);

        $this->actingAs($user)
            ->postJson(route('likes.toggle', $photo))
            ->assertOk()
            ->assertJson(['liked' => true]);

        $this->assertDatabaseHas('likes', ['user_id' => $user->id, 'photo_id' => $photo->id]);
    }

    public function test_user_can_unlike_photo(): void
    {
        $user  = User::factory()->create();
        $owner = User::factory()->create();
        $photo = $this->approvedPhoto($owner);

        Like::create(['user_id' => $user->id, 'photo_id' => $photo->id]);

        $this->actingAs($user)
            ->postJson(route('likes.toggle', $photo))
            ->assertOk()
            ->assertJson(['liked' => false]);

        $this->assertDatabaseMissing('likes', ['user_id' => $user->id, 'photo_id' => $photo->id]);
    }

    // ── Votes ─────────────────────────────────────────────────

    public function test_guest_cannot_access_favorites(): void
    {
        $this->get(route('votes.favorites'))->assertRedirect(route('login'));
    }

    public function test_user_can_submit_votes(): void
    {
        $user  = User::factory()->create();
        $owner = User::factory()->create();

        $photos = collect(range(1, 3))->map(fn () => $this->approvedPhoto($owner));

        foreach ($photos as $photo) {
            Like::create(['user_id' => $user->id, 'photo_id' => $photo->id]);
        }

        $votes = $photos->values()->map(fn ($p, $i) => [
            'photo_id' => $p->id,
            'rank'     => $i + 1,
        ])->all();

        $this->actingAs($user)
            ->postJson(route('votes.store'), ['votes' => $votes])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('votes', [
            'user_id'  => $user->id,
            'photo_id' => $photos->first()->id,
            'rank'     => 1,
        ]);
    }

    public function test_votes_reject_duplicate_ranks(): void
    {
        $user  = User::factory()->create();
        $owner = User::factory()->create();

        $photos = collect(range(1, 2))->map(fn () => $this->approvedPhoto($owner));

        foreach ($photos as $photo) {
            Like::create(['user_id' => $user->id, 'photo_id' => $photo->id]);
        }

        $votes = [
            ['photo_id' => $photos[0]->id, 'rank' => 1],
            ['photo_id' => $photos[1]->id, 'rank' => 1],
        ];

        $this->actingAs($user)
            ->post(route('votes.store'), ['votes' => $votes])
            ->assertSessionHasErrors('votes');
    }

    public function test_user_can_remove_a_vote(): void
    {
        $user  = User::factory()->create();
        $owner = User::factory()->create();
        $photo = $this->approvedPhoto($owner);

        Like::create(['user_id' => $user->id, 'photo_id' => $photo->id]);
        Vote::create(['user_id' => $user->id, 'photo_id' => $photo->id, 'rank' => 1]);

        $this->actingAs($user)
            ->deleteJson(route('votes.destroy', $photo))
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('votes', ['user_id' => $user->id, 'photo_id' => $photo->id]);
    }

    public function test_podium_is_publicly_accessible(): void
    {
        $this->get(route('votes.podium'))->assertOk();
    }
}

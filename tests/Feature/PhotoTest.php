<?php

namespace Tests\Feature;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_upload_form(): void
    {
        $this->get(route('photos.create'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_upload_form(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('photos.create'))->assertOk();
    }

    public function test_user_can_upload_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['viking_pseudo' => 'Björn']);

        $file = UploadedFile::fake()->image('costume.jpg', 800, 600);

        $response = $this->actingAs($user)->post(route('photos.store'), [
            'photo'         => $file,
            'viking_pseudo' => 'Björn le Valeureux',
            'caption'       => 'Mon costume',
        ]);

        $response->assertRedirect(route('gallery.index'));
        $this->assertDatabaseHas('photos', [
            'user_id'       => $user->id,
            'viking_pseudo' => 'Björn le Valeureux',
            'status'        => 'pending',
        ]);
        Storage::disk('public')->assertExists(Photo::first()->thumbnail_path);
    }

    public function test_photo_upload_requires_valid_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user)->post(route('photos.store'), [
            'photo'         => UploadedFile::fake()->create('doc.pdf', 100),
            'viking_pseudo' => 'Björn',
        ])->assertSessionHasErrors('photo');
    }

    public function test_user_can_view_their_photos(): void
    {
        $user = User::factory()->create();
        Photo::create([
            'user_id'        => $user->id,
            'original_path'  => 'photos/originals/test.jpg',
            'thumbnail_path' => 'photos/thumbnails/test_thumb.jpg',
            'viking_pseudo'  => 'Björn',
            'status'         => 'approved',
        ]);

        $this->actingAs($user)
            ->get(route('photos.mine'))
            ->assertOk()
            ->assertSee('Björn');
    }

    public function test_user_can_delete_own_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $photo = Photo::create([
            'user_id'        => $user->id,
            'original_path'  => 'photos/originals/test.jpg',
            'thumbnail_path' => 'photos/thumbnails/test_thumb.jpg',
            'viking_pseudo'  => 'Björn',
            'status'         => 'approved',
        ]);

        $this->actingAs($user)
            ->delete(route('photos.destroy', $photo))
            ->assertRedirect();

        $this->assertDatabaseMissing('photos', ['id' => $photo->id]);
    }

    public function test_user_cannot_delete_another_users_photo(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $photo = Photo::create([
            'user_id'        => $owner->id,
            'original_path'  => 'photos/originals/test.jpg',
            'thumbnail_path' => 'photos/thumbnails/test_thumb.jpg',
            'viking_pseudo'  => 'Björn',
            'status'         => 'approved',
        ]);

        $this->actingAs($other)
            ->delete(route('photos.destroy', $photo))
            ->assertForbidden();
    }
}

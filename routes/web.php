<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Galerie publique (lecture seule sans auth)
Route::get('/galerie', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/galerie/telecharger/{photo}', [GalleryController::class, 'download'])
    ->middleware('auth')
    ->name('gallery.download');

// Podium public
Route::get('/podium', [VoteController::class, 'podium'])->name('votes.podium');

// Routes authentifiées
Route::middleware('auth')->group(function () {

    // Profil Breeze
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Photos
    Route::get('/mes-photos', [PhotoController::class, 'myPhotos'])->name('photos.mine');
    Route::get('/poster-ma-photo', [PhotoController::class, 'create'])->name('photos.create');
    Route::post('/poster-ma-photo', [PhotoController::class, 'store'])->name('photos.store');
    Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

    // Likes (AJAX)
    Route::post('/photos/{photo}/like', [LikeController::class, 'toggle'])->name('likes.toggle');

    // Favoris + votes
    Route::get('/mes-favoris', [VoteController::class, 'favorites'])->name('votes.favorites');
    Route::post('/mes-favoris/voter', [VoteController::class, 'store'])->name('votes.store');
    Route::delete('/mes-favoris/{photo}/vote', [VoteController::class, 'destroyVote'])->name('votes.destroy');
});

// Administration
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/photos', [AdminController::class, 'photos'])->name('photos');
    Route::patch('/photos/{photo}/approuver', [AdminController::class, 'approve'])->name('photos.approve');
    Route::patch('/photos/{photo}/rejeter', [AdminController::class, 'reject'])->name('photos.reject');
    Route::delete('/photos/{photo}', [AdminController::class, 'destroy'])->name('photos.destroy');
    Route::get('/utilisateurs', [AdminController::class, 'users'])->name('users');
});

require __DIR__.'/auth.php';

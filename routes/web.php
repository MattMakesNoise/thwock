<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/rounds/create', [StartController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('rounds.create');

Route::post('/rounds/create', [StartController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('rounds.store');

Route::get('/rounds/{round}', [StartController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('rounds.show');

Route::patch('/rounds/{round}/scores/{holeScore}', [StartController::class, 'updateScore'])
    ->middleware(['auth', 'verified'])
    ->name('rounds.scores.update');

Route::post('/rounds/{round}/scorecards', [StartController::class, 'joinScorecard'])
    ->middleware(['auth', 'verified'])
    ->name('rounds.scorecards.store');

Route::post('/rounds/{round}/final-scores', [StartController::class, 'storeFinalScores'])
    ->middleware(['auth', 'verified'])
    ->name('rounds.final-scores.store');

Route::get('/courses/create', [CourseController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('courses.create');

Route::post('/courses', [CourseController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('courses.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

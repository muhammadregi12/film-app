<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });


// get movies
Route::get('/movies', function () {
    $query = Request::get('query');
    $genreId = Request::get('genre');

    if ($query) {
        $response = Http::get('https://api.themoviedb.org/3/search/movie', [
            'api_key' => config('services.tmdb.key'),
            'language' => 'id-ID',
            'query' => $query,
        ]);
    } elseif ($genreId) {
        $response = Http::get('https://api.themoviedb.org/3/discover/movie', [
            'api_key' => config('services.tmdb.key'),
            'language' => 'id-ID',
            'with_genres' => $genreId,
        ]);
    } else {
        $response = Http::get('https://api.themoviedb.org/3/movie/popular', [
            'api_key' => config('services.tmdb.key'),
            'language' => 'id-ID',
            'page' => 1,
        ]);
    }

    $genresResponse = Http::get('https://api.themoviedb.org/3/genre/movie/list', [
        'api_key' => config('services.tmdb.key'),
        'language' => 'id-ID',
    ]);

    return Inertia::render('Movies', [
        'movies' => $response->json()['results'] ?? [],
        'genres' => $genresResponse->json()['genres'] ?? [],
    ]);
});



// detail movies
Route::get('/movies/{id}', function ($id) {
    $movie = Http::get("https://api.themoviedb.org/3/movie/{$id}", [
        'api_key' => config('services.tmdb.key'),
        'language' => 'id-ID',
        'append_to_response' => 'credits,videos'
    ])->json();

    return Inertia::render('MovieDetail', [
        'movie' => $movie,
        'credits' => $movie['credits'] ?? null,
        'videos' => $movie['videos'] ?? null
    ]);
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

<?php
use Furkanakkulak\Rapidapitest\Http\Controllers\MovieController;
use Furkanakkulak\Rapidapitest\Http\Middleware\HandleApiErrors;
use Illuminate\Support\Facades\Route;

Route::middleware([HandleApiErrors::class])->prefix('api/movies')->group(function () {
    Route::get('/', [MovieController::class, 'index']);
    Route::get('/moviesdatabase', [MovieController::class, 'movieSearch']);
    Route::get('/advanced-movie-search', [MovieController::class, 'advancedMovieSearch']);
});

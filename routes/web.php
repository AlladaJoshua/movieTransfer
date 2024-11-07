<?php

use App\Http\Controllers\moveTransferController;
use App\Http\Controllers\movies;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/sample', function () {
    return view('sample');
});

Route::get('/movies/discover', [movies::class, 'discoverMovies']);

Route::get('/movies/search', action: [moveTransferController::class, 'movieTransfer']);

Route::post('/movies/save', [moveTransferController::class, 'saveMovie'])->name('movies.save');
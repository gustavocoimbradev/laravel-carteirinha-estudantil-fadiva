<?php

use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    $request->session()->forget('hash');
    return view('form');
})->name('form');

Route::post('/card', CardController::class)->name('card.proccess');
Route::get('/card/{hash}', CardController::class)->name('card.show');
Route::get('/card/{hash}/image', [CardController::class, 'generateImage'])->name('card.image');
Route::post('/card/{hash}/upload-photo', [CardController::class, 'uploadPhoto'])->name('card.upload-photo');
Route::get('/validate/{hash}', [CardController::class, 'validateCard'])->name('card.validate');


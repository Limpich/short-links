<?php

use Illuminate\Support\Facades\Route;

Route::post('/link', [App\Http\Controllers\LinkController::class, 'createFromApi']);
Route::delete('/link', [App\Http\Controllers\LinkController::class, 'deleteFromApi']);

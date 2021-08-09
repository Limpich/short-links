<?php

use App\Helpers\LinkHelper;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\LinkController::class, 'index']);

Route::get('/create', [App\Http\Controllers\LinkController::class, 'index']);
Route::post('/create', [App\Http\Controllers\LinkController::class, 'createFromForm']);

$shortLength = LinkHelper::SHORT_LENGTH;
Route::get('/{short}', [App\Http\Controllers\LinkController::class, 'redirect'])
    ->where(['short' => "[a-zA-Z0-9]{{$shortLength}}"]);

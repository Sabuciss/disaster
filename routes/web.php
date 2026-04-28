<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecipeController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/recipes/search', [RecipeController::class, 'search'])->name('recipes.search');

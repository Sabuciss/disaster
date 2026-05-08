<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;

// Favorītu apskates lapa
Route::get('/favorites', [RecipeController::class, 'favorites'])->name('recipes.favorites');



Route::get('/', function () {return view('welcome');});

Route::post('/recipes/search', [RecipeController::class, 'search'])->name('recipes.search');

// Savas receptes pievienošana
Route::post('/recipes/add', [RecipeController::class, 'add'])->name('recipes.add');

// Favorītu pievienošana
Route::post('/recipes/favorite', [RecipeController::class, 'favorite'])->name('recipes.favorite');

// Lietotāja pievienoto recepšu apskates lapa
Route::get('/my-recipes', [RecipeController::class, 'myRecipes'])->name('recipes.my');

// Jaunas receptes izveides forma
Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');

// API receptes detalizēta apskate pēc ID
Route::get('/recipes/view/api/{id}', [RecipeController::class, 'viewApi'])->name('recipes.view.api');
// Lietotāja receptes detalizēta apskate pēc indeksa
Route::get('/recipes/view/user/{index}', [RecipeController::class, 'viewUser'])->name('recipes.view.user');
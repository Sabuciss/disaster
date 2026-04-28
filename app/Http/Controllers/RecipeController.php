<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SpoonacularService;

class RecipeController extends Controller
{
    public function search(Request $request, SpoonacularService $spoonacularService)
    {
        $ingredients = $request->input('ingredients');
        $recipes = [];
        $error = null;

        if ($ingredients) {
            $recipes = $spoonacularService->findByIngredients($ingredients, 10);
            if (empty($recipes)) {
                $error = 'No recipes found for the given ingredients.';
            }
        } else {
            $error = 'Please enter ingredients.';
        }

        return view('welcome', compact('recipes', 'error', 'ingredients'));
    }
}

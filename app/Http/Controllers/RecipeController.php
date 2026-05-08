<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\SpoonacularService;

class RecipeController extends Controller
{
    // Jaunas receptes izveides forma
    public function create()
    {
        return view('create_recipe');
    }

    // Lietotāja pievienoto recepšu apskates lapa
    public function myRecipes()
    {
        $userRecipes = session('userRecipes', []);
        return view('my_recipes', compact('userRecipes'));
    }

    // Favorītu apskates lapa
    public function favorites()
    {
        $favorites = session('favorites', []);
        return view('favorites', compact('favorites'));
    }

    // Meklēšana (parāda arī lietotāja pievienotās receptes)
    public function search(Request $request, SpoonacularService $spoonacularService)
    {
        $ingredients = $request->input('ingredients');
        $recipes = [];
        $error = null;

        if ($ingredients) {
            $recipes = $spoonacularService->findByIngredients($ingredients, 10);
            // Pievieno arī lietotāja pievienotās receptes, kas atbilst sastāvdaļām
            $userRecipes = session('userRecipes', []);
            foreach ($userRecipes as $userRecipe) {
                $userIngredients = array_map('mb_strtolower', array_map('trim', $userRecipe['ingredients']));
                $inputIngredients = array_map('mb_strtolower', array_map('trim', explode(',', $ingredients)));
                // Ja vismaz viena sakrīt
                if (count(array_intersect($userIngredients, $inputIngredients)) > 0) {
                    $recipes[] = $userRecipe;
                }
            }
            if (empty($recipes)) {
                $error = 'No recipes found for the given ingredients.';
            }
        } else {
            $error = 'Please enter ingredients.';
        }

        return view('welcome', compact('recipes', 'error', 'ingredients'));
    }

    // Savas receptes pievienošana
    public function add(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'ingredients' => 'required|string',
            'readyInMinutes' => 'required|integer|min:1',
            'instructions' => 'required|string',
        ]);

        $recipe = [
            'title' => $request->input('title'),
            'ingredients' => array_map('trim', explode(',', $request->input('ingredients'))),
            'readyInMinutes' => $request->input('readyInMinutes'),
            'instructions' => $request->input('instructions'),
        ];

        $userRecipes = session('userRecipes', []);
        $userRecipes[] = $recipe;
        session(['userRecipes' => $userRecipes]);

        return redirect()->route('recipes.my')->with('success', 'Recepte pievienota!');
    }

    // Favorītu pievienošana
    public function favorite(Request $request)
    {
        $recipe = $request->input('recipe');
        if (is_string($recipe)) {
            $recipe = json_decode($recipe, true);
        }
        $favorites = session('favorites', []);
        $favorites[] = $recipe;
        session(['favorites' => $favorites]);
        return redirect('/')->with('success', 'Recepte pievienota favorītiem!');
    }

    // Receptes detalizēta apskate
    public function view($type, $index)
    {
        if ($type === 'user') {
            $recipes = session('userRecipes', []);
        } else {
            $recipes = session('recipes', []);
        }
        if (!isset($recipes[$index])) {
            abort(404, 'Recipe not found');
        }
        $recipe = $recipes[$index];
        return view('recipe_view', compact('recipe'));
    }

    // API receptes detalizēta apskate pēc ID
    public function viewApi($id, SpoonacularService $spoonacularService)
    {
        $recipe = $spoonacularService->information($id);
        if (!$recipe) {
            abort(404, 'Recipe not found');
        }
        return view('recipe_view', compact('recipe'));
    }

    // Lietotāja receptes detalizēta apskate pēc indeksa
    public function viewUser($index)
    {
        $recipes = session('userRecipes', []);
        if (!isset($recipes[$index])) {
            abort(404, 'Recipe not found');
        }
        $recipe = $recipes[$index];
        return view('recipe_view', compact('recipe'));
    }
}

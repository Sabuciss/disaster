<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gradient-to-br from-green-100 to-blue-100 min-h-screen flex flex-col items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg mt-8">
        <a href="/" class="text-blue-500 hover:underline mb-4 inline-block">← Back to search</a>
        <h1 class="text-3xl font-bold mb-4 text-green-700">{{ $recipe['title'] ?? 'Recipe' }}</h1>
        @if(isset($recipe['image']))
            <img src="{{ $recipe['image'] }}" alt="Image: {{ $recipe['title'] }}" class="w-full h-56 object-cover rounded-xl mb-4 shadow">
        @endif
        @if(isset($recipe['ingredients']))
            <h2 class="text-lg font-semibold mb-2">Ingredients:</h2>
            <ul class="mb-4 flex flex-wrap gap-2">
                @foreach(is_array($recipe['ingredients']) ? $recipe['ingredients'] : (is_string($recipe['ingredients']) ? explode(',', $recipe['ingredients']) : []) as $ingredient)
                    <li class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm flex items-center gap-1 border border-green-200">{{ is_array($ingredient) ? ($ingredient['name'] ?? json_encode($ingredient)) : $ingredient }}</li>
                @endforeach
            </ul>
        @elseif(isset($recipe['usedIngredients']) || isset($recipe['missedIngredients']))
            <h2 class="text-lg font-semibold mb-2">Ingredients:</h2>
            <ul class="mb-4 flex flex-wrap gap-2">
                @if(isset($recipe['usedIngredients']))
                    @foreach($recipe['usedIngredients'] as $ingredient)
                        <li class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm flex items-center gap-1 border border-green-200">✔️ {{ $ingredient['name'] }}</li>
                    @endforeach
                @endif
                @if(isset($recipe['missedIngredients']))
                    @foreach($recipe['missedIngredients'] as $ingredient)
                        <li class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm flex items-center gap-1 border border-red-200">❌ {{ $ingredient['name'] }}</li>
                    @endforeach
                @endif
            </ul>
        @endif

        @if(isset($recipe['extendedIngredients']) && is_array($recipe['extendedIngredients']) && count($recipe['extendedIngredients']))
            <h2 class="text-lg font-semibold mb-2 mt-4">Required ingredients:</h2>
            <ul class="mb-4 list-disc list-inside">
                @foreach($recipe['extendedIngredients'] as $ingredient)
                    <li class="text-gray-800">{{ $ingredient['original'] ?? $ingredient['name'] ?? json_encode($ingredient) }}</li>
                @endforeach
            </ul>
        @endif
        @if(isset($recipe['readyInMinutes']))
            <p class="text-sm text-gray-600 mb-1">Cooking time: <span class="font-semibold text-green-700">{{ $recipe['readyInMinutes'] }} min</span></p>
        @endif
        @if(isset($recipe['instructions']))
            <p class="text-base text-gray-700 mt-2"><span class="font-semibold">Instructions:</span> {{ $recipe['instructions'] }}</p>
        @endif
    </div>
</body>
</html>

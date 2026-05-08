<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mani favorīti</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gradient-to-br from-pink-100 to-yellow-100 min-h-screen flex flex-col items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl mt-8">
        <h1 class="text-3xl font-bold mb-4 text-center text-pink-700">Manas favorīt-receptes</h1>
        <a href="/" class="text-blue-500 hover:underline mb-4 inline-block">← Atpakaļ uz sākumu</a>
        @if(count($favorites))
            <div class="grid grid-cols-1 gap-4">
                @foreach($favorites as $recipe)
                    <div class="bg-pink-50 border border-pink-200 rounded-lg shadow p-4 flex flex-col">
                        <h3 class="text-xl font-bold text-pink-700 mb-2">{{ $recipe['title'] ?? 'Recepte' }}</h3>
                        @if(isset($recipe['image']))
                            <img src="{{ $recipe['image'] }}" alt="Attēls: {{ $recipe['title'] }}" class="w-full h-40 object-cover rounded mb-2">
                        @endif
                        @if(isset($recipe['ingredients']))
                            <ul class="mb-2">
                                @foreach(is_array($recipe['ingredients']) ? $recipe['ingredients'] : (is_string($recipe['ingredients']) ? explode(',', $recipe['ingredients']) : []) as $ingredient)
                                    <li class="text-pink-800">{{ is_array($ingredient) ? ($ingredient['name'] ?? json_encode($ingredient)) : $ingredient }}</li>
                                @endforeach
                            </ul>
                        @elseif(isset($recipe['usedIngredients']) || isset($recipe['missedIngredients']))
                            <ul class="mb-2">
                                @if(isset($recipe['usedIngredients']))
                                    @foreach($recipe['usedIngredients'] as $ingredient)
                                        <li class="text-green-800">✔️ {{ $ingredient['name'] }}</li>
                                    @endforeach
                                @endif
                                @if(isset($recipe['missedIngredients']))
                                    @foreach($recipe['missedIngredients'] as $ingredient)
                                        <li class="text-red-700">❌ {{ $ingredient['name'] }}</li>
                                    @endforeach
                                @endif
                            </ul>
                        @endif
                        @if(isset($recipe['readyInMinutes']))
                            <p class="text-sm text-gray-600 mb-1">Gatavošanas laiks: {{ $recipe['readyInMinutes'] }} min</p>
                        @endif
                        @if(isset($recipe['instructions']))
                            <p class="text-sm text-gray-700">Soļi: {{ $recipe['instructions'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500">Nav pievienotu favorītu.</p>
        @endif
    </div>
</body>
</html>

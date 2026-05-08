<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Food Waste Reduction</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gradient-to-br from-green-100 to-blue-100 min-h-screen flex flex-col items-center justify-center">
	<div class="w-full max-w-5xl flex justify-end gap-4 mt-4 pr-8">
		<a href="{{ route('recipes.favorites') }}" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition font-semibold">View Favorites</a>
		<a href="{{ route('recipes.create') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition font-semibold">Create Recipe</a>
	</div>
	<div class="flex flex-col md:flex-row gap-8 w-full max-w-5xl justify-center items-start mt-8">
		<!-- Left column: form and recipes -->
		<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
			<h1 class="text-3xl font-bold mb-2 text-center text-green-700">Food Waste Reduction</h1>
			<p class="text-center text-gray-600 mb-6">Enter the products you have in your fridge and find recipes! <span class="font-semibold text-red-600">(Search in English)</span></p>
			<form method="POST" action="{{ route('recipes.search') }}" class="mb-4">
			@csrf
			<label for="ingredients" class="block text-gray-700 mb-2 font-semibold">Products (comma separated, in English):</label>
			<input type="text" id="ingredients" name="ingredients" class="w-full p-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-green-300" placeholder="milk, cheese, pasta, chicken">

			<!-- Cooking time filter -->
			<label for="maxReadyTime" class="block text-gray-700 mb-2 font-semibold">Max cooking time (min):</label>
			<input type="number" id="maxReadyTime" name="maxReadyTime" min="1" class="w-full p-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="30">

			<button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition font-semibold">Search Recipes</button>
			</form>

			@if(session('error'))
				<div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-center animate-pulse">
					{{ session('error') }}
				</div>
			@endif
			@php
				$allRecipes = session('recipes') ?? (isset($recipes) ? $recipes : null);
			@endphp
			@if($allRecipes && count($allRecipes))
				<div class="mb-4">
					<h2 class="text-lg font-semibold mb-4 text-green-600 text-center">Possible recipes:</h2>
					<div class="grid grid-cols-1 gap-4">
						@foreach($allRecipes as $i => $recipe)
							@if(is_array($recipe) && isset($recipe['title']))
								<div class="bg-white border border-green-200 rounded-2xl shadow-lg p-6 flex flex-col relative transition-transform transform hover:-translate-y-1 hover:shadow-2xl duration-200">
									<h3 class="text-xl font-bold text-green-700 mb-2 flex items-center justify-between">
										{{ $recipe['title'] }}
										<!-- Favorītu poga -->
										<form method="POST" action="{{ route('recipes.favorite') }}" class="inline">
											@csrf
											<input type="hidden" name="recipe" value='@json($recipe)'>
											<button type="submit" title="Pievienot favorītiem" class="ml-2 text-pink-500 hover:text-pink-700 text-2xl">&#10084;</button>
										</form>
									</h3>
									@php $isUserRecipe = isset($recipe['ingredients']) && !isset($recipe['image']); @endphp
									@if($isUserRecipe)
										<ul class="mb-4 flex flex-wrap gap-2">
											@foreach(is_array($recipe['ingredients']) ? $recipe['ingredients'] : (is_string($recipe['ingredients']) ? explode(',', $recipe['ingredients']) : []) as $ingredient)
												<li class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm flex items-center gap-1 border border-green-200">{{ is_array($ingredient) ? ($ingredient['name'] ?? json_encode($ingredient)) : $ingredient }}</li>
											@endforeach
										</ul>
									@else
										@if(isset($recipe['image']))
											<img src="{{ $recipe['image'] }}" alt="Image: {{ $recipe['title'] }}" class="w-full h-48 object-cover rounded-xl mb-4 shadow">
										@endif
										@if(isset($recipe['usedIngredients']) || isset($recipe['missedIngredients']))
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
										@if(isset($recipe['readyInMinutes']))
											<p class="text-sm text-gray-600 mb-1">Cooking time: <span class="font-semibold text-green-700">{{ $recipe['readyInMinutes'] }} min</span></p>
										@endif
										@if(isset($recipe['instructions']))
											<p class="text-sm text-gray-700"><span class="font-semibold">Instructions:</span> {{ $recipe['instructions'] }}</p>
										@endif
									@endif
									<div class="flex justify-end mt-4">
										@php
											$isUserRecipe = isset($recipe['ingredients']) && !isset($recipe['image']);
										@endphp
										@if($isUserRecipe)
											<a href="{{ route('recipes.view.user', ['index' => $i]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition font-semibold">View details</a>
										@elseif(isset($recipe['id']))
											<a href="{{ route('recipes.view.api', ['id' => $recipe['id']]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition font-semibold">View details</a>
										@endif
									</div>
								</div>
							@else
								<div class="bg-green-50 border border-green-200 rounded-lg shadow p-4 flex flex-col">
									{{ is_array($recipe) ? json_encode($recipe) : $recipe }}
								</div>
							@endif
						@endforeach
					</div>
				</div>
			@endif
		</div>
		<!-- Right column: minigame, always visible -->
		<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md flex flex-col items-center">
			<h2 class="text-xl font-bold mb-2 text-blue-600">Mini-game: Raining Food</h2>
			<canvas id="gameCanvas" width="400" height="600" class="border border-gray-300 rounded mb-2"></canvas>
			<div class="flex flex-col items-center w-full">
				<button onclick="startGame()" type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition mb-2">Play</button>
				<span id="score" class="font-semibold text-green-700 mb-1">Score: 0</span>
				<span id="gameOver" class="text-red-500 font-bold"></span>
				</div>
				<p class="text-xs text-gray-400 mt-2">Catch only fruits and vegetables! If you catch a junk item, the game ends.</p>
		</div>
	</div>

	<script src="/js/minigame.js"></script>
</body>
</html>
   
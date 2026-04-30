<!DOCTYPE html>
<html lang="lv">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Pārtikas Atkritumu Samazināšana</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gradient-to-br from-green-100 to-blue-100 min-h-screen flex flex-col items-center justify-center">
	<div class="flex flex-col md:flex-row gap-8 w-full max-w-5xl justify-center items-start mt-8">
		<!-- Kreisā kolonna: forma un receptes -->
		<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
			<h1 class="text-3xl font-bold mb-2 text-center text-green-700">Pārtikas Atkritumu Samazināšana</h1>
			<p class="text-center text-gray-600 mb-6">Ievadi ledusskapī pieejamos produktus un atrodi receptes!</p>
			<form method="POST" action="{{ route('recipes.search') }}" class="mb-4">
				@csrf
				<label for="ingredients" class="block text-gray-700 mb-2 font-semibold">Produkti (atdala ar komatu):</label>
				<input type="text" id="ingredients" name="ingredients" class="w-full p-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-green-300" placeholder="piens, siers, makaroni, vista">
				<button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition font-semibold">Meklēt receptes</button>
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
					<h2 class="text-lg font-semibold mb-2 text-green-600">Iespējamās receptes:</h2>
					<ul class="list-disc pl-5">
						@foreach($allRecipes as $recipe)
							<li>
								@if(is_array($recipe) && isset($recipe['title']))
									{{ $recipe['title'] }}
								@else
									{{ $recipe }}
								@endif
							</li>
						@endforeach
					</ul>
				</div>
			@endif
		</div>
		<!-- Labā kolonna: minispēle, nav pogas, uzreiz redzama -->
		<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md flex flex-col items-center">
			<h2 class="text-xl font-bold mb-2 text-blue-600">Mini-spēle: Raining Food</h2>
			<canvas id="gameCanvas" width="400" height="600" class="border border-gray-300 rounded mb-2"></canvas>
			<div class="flex flex-col items-center w-full">
				<button onclick="startGame()" type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition mb-2">Spēlēt</button>
				<span id="score" class="font-semibold text-green-700 mb-1">Punkti: 0</span>
				<span id="gameOver" class="text-red-500 font-bold"></span>
			</div>
			<p class="text-xs text-gray-400 mt-2">Ķer tikai augļus un dārzeņus! Ja noķer svešķermeni, spēle beidzas.</p>
		</div>
	</div>
	<footer class="mt-8 text-gray-500 text-sm text-center">&copy; {{ date('Y') }} Pārtikas Atkritumu Samazināšanas Projekts</footer>

	<script src="/js/minigame.js"></script>
</body>
</html>
   
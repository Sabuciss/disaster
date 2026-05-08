<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pievienot jaunu recepti</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gradient-to-br from-yellow-100 to-green-100 min-h-screen flex flex-col items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md mt-8">
        <h1 class="text-2xl font-bold mb-4 text-center text-green-700">Pievienot jaunu recepti</h1>
        <a href="/" class="text-blue-500 hover:underline mb-4 inline-block">← Atpakaļ uz sākumu</a>
        <form method="POST" action="{{ route('recipes.add') }}" class="space-y-2">
            @csrf
            <input type="text" name="title" required class="w-full p-2 border border-gray-300 rounded" placeholder="Receptes nosaukums">
            <input type="text" name="ingredients" required class="w-full p-2 border border-gray-300 rounded" placeholder="Sastāvdaļas (atdala ar komatu)">
            <input type="number" name="readyInMinutes" min="1" required class="w-full p-2 border border-gray-300 rounded" placeholder="Gatavošanas laiks (min)">
            <textarea name="instructions" required class="w-full p-2 border border-gray-300 rounded" placeholder="Pagatavošanas soļi"></textarea>
            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition font-semibold">Pievienot recepti</button>
        </form>
    </div>
</body>
</html>

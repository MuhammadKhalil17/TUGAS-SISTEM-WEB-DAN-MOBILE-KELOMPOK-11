<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kulkasku</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100">

<nav class="bg-green-600 shadow">

    <div class="container mx-auto px-6 py-4 flex justify-between">

        <h1 class="text-white text-2xl font-bold">
            🥬 Kulkasku
        </h1>

        <div class="space-x-5">

            <a href="/dashboard" class="text-white">
                Dashboard
            </a>

            <a href="/fridge" class="text-white">
                Kulkas
            </a>

            <a href="/recipes" class="text-white">
                Resep
            </a>

            <a href="/favorites" class="text-white">
                Favorit
            </a>

        </div>

    </div>

</nav>

<div class="container mx-auto p-6">

    @yield('content')

</div>

</body>
</html>
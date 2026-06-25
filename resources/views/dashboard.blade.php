@extends('layouts.app')

@section('content')

<h2 class="text-3xl font-bold mb-6">
    Dashboard
</h2>

<div class="grid md:grid-cols-3 gap-6">

    <div class="bg-white p-6 rounded-xl shadow">

        <p class="text-gray-500">
            Total Bahan
        </p>

        <h1 class="text-5xl font-bold text-green-600">
            12
        </h1>

    </div>

    <div class="bg-white p-6 rounded-xl shadow">

        <p class="text-gray-500">
            Resep Favorit
        </p>

        <h1 class="text-5xl font-bold text-yellow-500">
            5
        </h1>

    </div>

    <div class="bg-white p-6 rounded-xl shadow">

        <p class="text-gray-500">
            Hampir Kadaluarsa
        </p>

        <h1 class="text-5xl font-bold text-red-500">
            2
        </h1>

    </div>

</div>

@endsection
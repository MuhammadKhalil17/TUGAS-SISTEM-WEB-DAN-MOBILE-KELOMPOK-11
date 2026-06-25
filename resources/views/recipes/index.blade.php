@extends('layouts.app')

@section('content')

<h2 class="text-3xl font-bold mb-6">
    Cari Resep
</h2>

<button
    class="bg-green-600 text-white px-4 py-2 rounded-lg mb-6">

    Temukan Resep

</button>

<div class="grid md:grid-cols-2 gap-6">

    <div class="bg-white p-6 rounded-xl shadow">

        <h3 class="font-bold text-xl">
            🍳 Nasi Goreng
        </h3>

        <p class="text-gray-500 mt-2">
            20 Menit
        </p>

        <button
            class="bg-blue-500 text-white px-3 py-2 rounded mt-4">

            Detail

        </button>

    </div>

</div>

@endsection
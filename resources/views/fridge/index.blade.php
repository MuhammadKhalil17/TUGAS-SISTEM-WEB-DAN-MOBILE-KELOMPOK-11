@extends('layouts.app')

@section('content')

<div class="flex justify-between mb-6">

    <h2 class="text-3xl font-bold">
        Isi Kulkas
    </h2>

    <button
        class="bg-green-600 text-white px-4 py-2 rounded-lg">
        Tambah Bahan
    </button>

</div>

<input
    type="text"
    placeholder="Cari bahan..."
    class="w-full border p-3 rounded mb-5"
/>

<div class="space-y-4">

    <div class="bg-white p-4 rounded shadow">
        🥚 Telur
    </div>

    <div class="bg-white p-4 rounded shadow">
        🍅 Tomat
    </div>

    <div class="bg-white p-4 rounded shadow">
        🧅 Bawang
    </div>

</div>

@endsection
@extends('layouts.app')

@section('content')

<h2 class="text-3xl font-bold mb-6">
    Dashboard
</h2>

<div class="grid md:grid-cols-3 gap-6">

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-gray-500">Total Bahan</p>
        <h1 id="total-bahan" class="text-5xl font-bold text-green-600">
            0
        </h1>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-gray-500">Resep Favorit</p>
        <h1 id="resep-favorit" class="text-5xl font-bold text-yellow-500">
            0
        </h1>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-gray-500">Hampir Kadaluarsa</p>
        <h1 class="text-5xl font-bold text-red-500">
            0
        </h1>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Ambil data Total Bahan dari FridgeController melalui API
    fetch('/api/v1/refrigerator')
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengambil data kulkas');
            return response.json();
        })
        .then(data => {
            // Cek apakah response berupa array atau objek pembungkus
            const total = Array.isArray(data) ? data.length : (data.ingredients ? data.ingredients.length : 0);
            document.getElementById('total-bahan').innerText = total;
        })
        .catch(err => console.error("Error Kulkas:", err));

    // 2. Ambil data Resep Favorit dari FavoriteRecipeController melalui API
    fetch('/api/v1/favorite-recipes')
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengambil data favorit');
            return response.json();
        })
        .then(data => {
            const totalFavorit = Array.isArray(data) ? data.length : 0;
            document.getElementById('resep-favorit').innerText = totalFavorit;
        })
        .catch(err => console.error("Error Favorit:", err));
});
</script>

@endsection
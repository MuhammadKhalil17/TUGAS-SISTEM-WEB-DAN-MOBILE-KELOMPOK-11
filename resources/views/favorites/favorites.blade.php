@extends('layouts.app')

@section('content')

<h2 class="text-3xl font-bold mb-6">
    ❤️ Resep Favorit
</h2>

<div id="daftar-favorit" class="space-y-4">
    <div class="text-gray-500 text-center py-4">Memuat resep favorit kamu...</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    muatResepFavorit();
});

// 1. Fungsi Mandiri untuk Mengambil Data Resep Favorit dari API Backend
function muatResepFavorit() {
    const container = document.getElementById('daftar-favorit');

    fetch('/api/v1/favorite-recipes')
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengambil data resep favorit');
            return response.json();
        })
        .then(data => {
            container.innerHTML = ''; // Bersihkan tulisan memuat / data lama
            
            // Laravel merespon langsung dengan array data
            const resepList = data || [];

            if (resepList.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-gray-400 py-8 bg-white rounded shadow p-6">
                        Belum ada resep yang kamu simpan ke favorit. 
                        Yuk cari resep menarik di menu <a href="/recipes" class="text-green-600 font-semibold hover:underline">Resep</a>!
                    </div>`;
                return;
            }

            // Lakukan looping data untuk memunculkan kotak komponen secara dinamis
            resepList.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.className = "bg-white p-4 rounded shadow flex justify-between items-center animate-fade-in";
                itemDiv.innerHTML = `
                    <div class="flex items-center space-x-4">
                        ${item.image ? `<img src="${item.image}" alt="${item.title}" class="w-12 h-12 object-cover rounded-lg">` : '🍳'}
                        <span class="font-medium text-gray-700 text-lg">${item.title}</span>
                    </div>
                    <button onclick="hapusDariFavorit(${item.recipe_id})" class="text-red-500 hover:text-red-700 font-semibold text-sm">
                        Hapus Favorit
                    </button>
                `;
                container.appendChild(itemDiv);
            });
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = `<div class="text-center text-red-500 py-6 bg-white rounded shadow">Gagal memuat resep favorit. Server API offline.</div>`;
        });
}

// 2. Fungsi untuk Menghapus Resep dari Daftar Favorit (DELETE) via API
function hapusDariFavorit(recipeId) {
    if (!confirm('Apakah kamu yakin ingin menghapus resep ini dari daftar favorit?')) return;

    fetch(`/api/v1/favorite-recipes/${recipeId}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json' }
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal menghapus resep dari favorit');
        return response.json();
    })
    .then(data => {
        muatResepFavorit(); // Segarkan kembali daftar resep di layar
    })
    .catch(err => alert('Gagal menghapus resep favorit.'));
}
</script>

@endsection
@extends('layouts.app')

@section('content')

<h2 class="text-3xl font-bold mb-6">
    🍳 Generator Rekomendasi Resep
</h2>

<button
    onclick="generateResepDariKulkas()"
    class="bg-green-600 text-white px-5 py-3 rounded-lg mb-6 font-semibold hover:bg-green-700 transition shadow">
    🔄 Temukan Resep Berdasarkan Isi Kulkas
</button>

<div id="daftar-resep" class="grid md:grid-cols-2 gap-6">
    <div class="text-gray-500 bg-white p-6 rounded-xl shadow col-span-2 text-center">
        Klik tombol di atas untuk mencocokkan bahan kulkas kamu dengan resep masakan global!
    </div>
</div>

<script>
// 1. Ambil Bahan Kulkas (GET) -> Kirim ke Recipe Generator (POST)
function generateResepDariKulkas() {
    const container = document.getElementById('daftar-resep');
    container.innerHTML = `<div class="text-gray-500 col-span-2 text-center py-6">Mengumpulkan bahan dari kulkas dan mencari resep cocok...</div>`;

    fetch('/api/v1/fridge', { // Diubah dari /refrigerator menjadi /fridge
        headers: { 'Accept': 'application/json' }
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal membaca isi kulkas');
        return response.json();
    })
    .then(res => {
        // Menyesuaikan struktur response backend: res.data.ingredients
        const ingredientsList = res.data?.ingredients || [];
        
        if (ingredientsList.length === 0) {
            container.innerHTML = `
                <div class="text-red-500 col-span-2 text-center py-6 bg-white rounded shadow p-4">
                    Kulkas kamu kosong! Isi bahan makanan dulu di menu <a href="/fridge" class="underline font-bold text-green-600">Kulkas</a> sebelum mencari resep.
                </div>`;
            return;
        }

        const namaBahanArray = ingredientsList.map(item => item.name);

        return fetch('/api/v1/recipes/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ingredients: namaBahanArray })
        });
    })
    .then(response => {
        if (!response) return; 
        if (!response.ok) throw new Error('Gagal mencocokkan resep dari API');
        return response.json();
    })
    .then(recipes => {
        if (!recipes || recipes.length === 0) {
            container.innerHTML = `<div class="text-gray-500 col-span-2 text-center py-6">Tidak ditemukan resep global yang cocok dengan kombinasi bahan kulkasmu.</div>`;
            return;
        }

        container.innerHTML = ''; 

        recipes.forEach(resep => {
            const card = document.createElement('div');
            card.className = "bg-white p-6 rounded-xl shadow flex flex-col justify-between animate-fade-in";
            card.innerHTML = `
                <div>
                    <img src="${resep.image}" alt="${resep.title}" class="w-full h-48 object-cover rounded-lg mb-4 shadow-sm">
                    <h3 class="font-bold text-xl text-gray-800 mb-2">🍳 ${resep.title}</h3>
                    <p class="text-sm text-green-600 font-medium">✅ Pakai ${resep.usedIngredientCount} bahan dari kulkas</p>
                    <p class="text-sm text-amber-600 font-medium mb-4">⚠️ Kurang ${resep.missedIngredientCount} bahan lagi</p>
                </div>
                <div class="flex space-x-2 mt-4">
                    <button onclick="simpanKeFavorit('${resep.id}', '${resep.title.replace(/'/g, "\\'")}', '${resep.image}')" class="bg-amber-500 text-white px-3 py-2 rounded font-medium hover:bg-amber-600 transition text-sm flex-1">
                        ❤️ Simpan Favorit
                    </button>
                </div>
            `;
            container.appendChild(card);
        });
    })
    .catch(err => {
        console.error(err);
        container.innerHTML = `<div class="text-center text-red-500 col-span-2 py-6 bg-white rounded shadow">Gagal menghubungkan pencarian ke server Spoonacular API.</div>`;
    });
}

// 2. Simpan Resep Favorit ke Bookmarks (POST)
function simpanKeFavorit(recipeId, title, image) {
    fetch('/api/v1/bookmarks', { // Diubah dari /favorite-recipes menjadi /bookmarks
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            recipe_id: parseInt(recipeId),
            title: title,
            image: image
        })
    })
    .then(response => {
        if (response.status === 409) {
            alert('Resep ini sudah ada di daftar favorit kamu sebelumnya!');
            return;
        }
        if (!response.ok) throw new Error('Gagal menyimpan');
        alert(`Sukses! Resep "${title}" berhasil disimpan ke buku resep favorit.`);
    })
    .catch(err => alert('Gagal menyimpan resep ke favorit.'));
}
</script>

@endsection
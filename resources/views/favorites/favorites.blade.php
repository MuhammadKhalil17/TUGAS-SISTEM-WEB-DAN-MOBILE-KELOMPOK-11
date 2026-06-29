@extends('layouts.app')

@section('content')

<div class="mb-8 animate-slide-up">
    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2 inline-block">
        Buku Resep Pribadi
    </span>
    <h2 class="text-3xl font-black text-gray-800 tracking-tight">
        Resep Favorit Saya ❤️
    </h2>
</div>

<div id="daftar-favorit" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="text-gray-500 text-center py-12 bg-white rounded-3xl border border-gray-100 shadow-sm p-6 col-span-full">
        Memuat resep favorit Anda...
    </div>
</div>

<script>
// Global headers with Auth Token
const activeToken = localStorage.getItem('auth_token');
const globalHeaders = {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
};
if (activeToken) {
    globalHeaders['Authorization'] = `Bearer ${activeToken}`;
}

document.addEventListener("DOMContentLoaded", function() {
    muatResepFavorit();
});

// 1. Fungsi Mandiri untuk Mengambil Data Resep Favorit dari API Backend
function muatResepFavorit() {
    const container = document.getElementById('daftar-favorit');

    fetch('/api/v1/favorite-recipes', {
        headers: globalHeaders
    })
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengambil data resep favorit');
            return response.json();
        })
        .then(res => {
            container.innerHTML = ''; // Bersihkan tulisan memuat / data lama
            
            // Handle both wrapped `{ data: [...] }` and direct array response
            const resepList = (res && res.data) ? res.data : (Array.isArray(res) ? res : []);

            if (resepList.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-gray-400 py-16 bg-white rounded-3xl border border-gray-100 shadow-sm p-8 col-span-full animate-fade-in">
                        <span class="text-6xl block mb-3">📖</span>
                        <h3 class="font-extrabold text-gray-700 text-lg mb-1">Buku Resep Masih Kosong</h3>
                        <p class="text-gray-400 font-medium text-sm mb-4">Belum ada resep favorit yang Anda simpan.</p>
                        <a href="/recipes" class="bg-teal-650 hover:bg-teal-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow transition active:scale-95 inline-block">
                            Temukan Rekomendasi Resep
                        </a>
                    </div>`;
                return;
            }

            // Lakukan looping data untuk memunculkan kotak komponen secara dinamis
            resepList.forEach(item => {
                const card = document.createElement('div');
                card.className = "bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col justify-between hover:shadow-md transition duration-300 hover:scale-[1.01] animate-fade-in";
                card.innerHTML = `
                    <div>
                        ${item.image ? `<img src="${item.image}" alt="${item.title}" class="w-full h-48 object-cover shadow-sm">` : `<div class="w-full h-48 bg-teal-50 flex items-center justify-center text-5xl">🍳</div>`}
                        <div class="p-6">
                            <h3 class="font-extrabold text-lg text-gray-800 tracking-tight line-clamp-2">🍳 ${item.title}</h3>
                        </div>
                    </div>
                    <div class="px-6 pb-6 pt-2 flex gap-2">
                        <button onclick="hapusDariFavorit(${item.spoonacular_recipe_id || item.recipe_id})" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold py-2.5 px-4 rounded-xl text-xs text-center transition flex-1 active:scale-95 border border-rose-150">
                            Hapus Favorit
                        </button>
                    </div>
                `;
                container.appendChild(card);
            });
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = `<div class="text-center text-red-500 py-12 bg-white border border-red-100 rounded-3xl shadow col-span-full">Gagal memuat resep favorit.</div>`;
        });
}

// 2. Fungsi untuk Menghapus Resep dari Daftar Favorit (DELETE) via API
function hapusDariFavorit(recipeId) {
    window.showConfirmModal(
        'Hapus Resep Favorit',
        'Apakah kamu yakin ingin menghapus resep ini dari daftar resep favorit?',
        function() {
            fetch(`/api/v1/favorite-recipes/${recipeId}`, {
                method: 'DELETE',
                headers: globalHeaders
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal menghapus resep dari favorit');
                return response.json();
            })
            .then(data => {
                window.showToast('Resep berhasil dihapus dari daftar favorit.');
                muatResepFavorit(); // Segarkan kembali daftar resep di layar
            })
            .catch(err => window.showToast('Gagal menghapus resep favorit.', 'error'));
        },
        '🗑️'
    );
}
</script>

@endsection
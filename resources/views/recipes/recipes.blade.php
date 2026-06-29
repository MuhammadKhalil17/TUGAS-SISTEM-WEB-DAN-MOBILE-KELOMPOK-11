@extends('layouts.app')

@section('content')

<div class="mb-8 animate-slide-up">
    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2 inline-block">
        Rekomendasi Pintar Spoonacular
    </span>
    <h2 class="text-3xl font-black text-gray-800 tracking-tight">
        Generator Rekomendasi Resep 🍳
    </h2>
</div>

<button
    onclick="generateResepDariKulkas()"
    class="w-full sm:w-auto bg-gradient-to-r from-emerald-600 to-teal-650 text-white px-6 py-3.5 rounded-2xl mb-8 font-bold hover:from-emerald-700 hover:to-teal-700 transition shadow hover:shadow-emerald-600/20 active:scale-95 text-sm flex items-center justify-center gap-2 animate-fade-in">
    🔄 Temukan Resep Berdasarkan Isi Kulkas
</button>

<div id="daftar-resep" class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
    <div class="text-gray-500 bg-white p-8 sm:p-12 rounded-3xl border border-gray-100 shadow-sm col-span-full text-center py-12 sm:py-16 animate-fade-in">
        <span class="text-6xl block mb-4 animate-bounce-slow">💡</span>
        <h3 class="font-extrabold text-gray-700 text-lg mb-1">Cari Inspirasi Hidangan</h3>
        <p class="font-medium text-gray-400 text-sm">Klik tombol di atas untuk mencocokkan bahan kulkas Anda dengan jutaan resep global!</p>
    </div>
</div>

<!-- Modal Detail Resep -->
<div id="recipe-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-50 flex items-end sm:items-center justify-center sm:p-4 transition duration-300">
    <div class="bg-white rounded-t-3xl sm:rounded-3xl max-w-xl w-full max-h-[90vh] sm:max-h-[85vh] overflow-y-auto shadow-2xl border border-gray-100 flex flex-col animate-fade-in">
        <!-- Header -->
        <div class="p-4 sm:p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-3xl sticky top-0">
            <h3 id="modal-title" class="font-black text-base sm:text-xl text-gray-800 tracking-tight pr-4">Detail Resep</h3>
            <button onclick="tutupModal()" class="text-gray-400 hover:text-gray-600 font-bold text-3xl outline-none leading-none flex-shrink-0">×</button>
        </div>
        <!-- Content -->
        <div id="modal-body" class="p-4 sm:p-6">
            <div class="text-center py-12 text-gray-400">Memuat detail resep...</div>
        </div>
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

// 1. Ambil Bahan Kulkas (GET) -> Kirim ke Recipe Generator (POST)
function generateResepDariKulkas() {
    const container = document.getElementById('daftar-resep');
    container.innerHTML = `
        <div class="col-span-2 text-center py-16 bg-white rounded-3xl border border-gray-150 shadow p-6">
            <div class="inline-block w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin mb-4"></div>
            <p class="text-gray-500 font-semibold">Mengumpulkan bahan dari kulkas dan mencari kecocokan resep...</p>
        </div>`;

    fetch('/api/v1/fridge', {
        headers: globalHeaders
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal membaca isi kulkas');
        return response.json();
    })
    .then(res => {
        const ingredientsList = res.data?.ingredients || [];
        
        if (ingredientsList.length === 0) {
            container.innerHTML = `
                <div class="text-red-500 col-span-2 text-center py-16 bg-white rounded-3xl border border-rose-100 shadow p-8 animate-fade-in">
                    <span class="text-5xl block mb-2">🥬</span>
                    <h3 class="font-extrabold text-gray-700 mb-1">Kulkas Anda Kosong</h3>
                    <p class="text-gray-400 font-medium text-sm mb-4">Harap isi stok bahan makanan terlebih dahulu di menu Kulkas.</p>
                    <a href="/fridge" class="bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow transition active:scale-95 inline-block">
                        Pergi Ke Kulkas
                    </a>
                </div>`;
            return;
        }

        const namaBahanArray = ingredientsList.map(item => item.name);

        return fetch('/api/v1/recipes/search', {
            method: 'POST',
            headers: globalHeaders,
            body: JSON.stringify({ ingredients: namaBahanArray })
        });
    })
    .then(response => {
        if (!response) return; 
        if (!response.ok) throw new Error('Gagal mencocokkan resep dari API');
        return response.json();
    })
    .then(recipes => {
        const recipesList = (recipes && recipes.data) ? recipes.data : (Array.isArray(recipes) ? recipes : []);
        if (recipesList.length === 0) {
            container.innerHTML = `
                <div class="text-gray-500 bg-white border border-gray-100 col-span-2 text-center py-16 rounded-3xl shadow">
                    <span class="text-5xl block mb-2">🍽️</span>
                    <h3 class="font-extrabold text-gray-750 mb-1">Tidak Ada Resep yang Cocok</h3>
                    <p class="text-gray-400 font-medium text-sm">Coba tambahkan lebih banyak variasi bahan dapur di kulkas Anda.</p>
                </div>`;
            return;
        }

        container.innerHTML = ''; 

        recipesList.forEach(resep => {
            const card = document.createElement('div');
            card.className = "bg-white rounded-3xl shadow-sm border border-gray-100/80 overflow-hidden flex flex-col justify-between hover:shadow-md transition duration-300 hover:scale-[1.01] animate-fade-in";
            card.innerHTML = `
                <div>
                    <img src="${resep.image}" alt="${resep.title}" class="w-full h-52 object-cover shadow-sm">
                    <div class="p-6">
                        <h3 class="font-extrabold text-xl text-gray-850 mb-3 tracking-tight">🍳 ${resep.title}</h3>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <span class="bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                                🟢 Cocok: ${resep.usedIngredientCount} bahan
                            </span>
                            <span class="bg-amber-55/60 text-amber-800 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                                🟡 Kurang: ${resep.missedIngredientCount} bahan
                            </span>
                        </div>
                    </div>
                </div>
                <div class="px-4 sm:px-6 pb-4 sm:pb-6 pt-2 flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button onclick="bukaDetailResep('${resep.id}')" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-3 rounded-2xl font-bold transition text-sm flex-1 active:scale-95 shadow hover:shadow-teal-650/10">
                        👨‍🍳 Cara Masak
                    </button>
                    <button onclick="simpanKeFavorit('${resep.id}', '${resep.title.replace(/'/g, "\\'")}', '${resep.image}')" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-3 rounded-2xl font-bold transition text-sm flex-1 active:scale-95 shadow hover:shadow-amber-500/10">
                        ❤️ Simpan Favorit
                    </button>
                </div>
            `;
            container.appendChild(card);
        });
    })
    .catch(err => {
        console.error(err);
        container.innerHTML = `<div class="text-center text-red-500 col-span-2 py-12 bg-white rounded-3xl shadow">Gagal menghubungkan pencarian resep ke server Spoonacular.</div>`;
    });
}

// 2. Simpan Resep Favorit ke Bookmarks (POST)
function simpanKeFavorit(recipeId, title, image) {
    fetch('/api/v1/bookmarks', {
        method: 'POST',
        headers: globalHeaders,
        body: JSON.stringify({
            recipe_id: parseInt(recipeId),
            title: title,
            image: image
        })
    })
    .then(response => {
        if (response.status === 409) {
            window.showToast('Resep ini sudah ada di daftar favorit kamu!', 'error');
            return;
        }
        if (!response.ok) throw new Error('Gagal menyimpan');
        window.showToast(`Resep "${title}" berhasil disimpan ke favorit! ❤️`);
    })
    .catch(err => window.showToast('Gagal menyimpan resep ke favorit.', 'error'));
}

// 3. Detail Resep Modal dengan Tab Switching
let currentRecipeData = null;

function bukaDetailResep(recipeId) {
    const modal = document.getElementById('recipe-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalBody = document.getElementById('modal-body');
    
    modal.classList.remove('hidden');
    modalBody.innerHTML = `
        <div class="text-center py-12">
            <div class="inline-block w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin mb-3"></div>
            <p class="text-gray-400 text-sm font-semibold">Memuat resep lengkap...</p>
        </div>`;
    
    fetch(`/api/v1/recipes/${recipeId}/details`, {
        headers: globalHeaders
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal memuat resep');
        return response.json();
    })
    .then(res => {
        const resep = res.data;
        if (!resep) throw new Error('Resep tidak ditemukan');
        
        currentRecipeData = resep;
        modalTitle.innerText = `🍳 ${resep.title}`;
        
        let ingredientsHTML = '';
        if (resep.extendedIngredients && resep.extendedIngredients.length > 0) {
            resep.extendedIngredients.forEach(ing => {
                ingredientsHTML += `<li class="text-gray-650 text-sm flex items-start gap-2 py-1.5 border-b border-gray-50">
                    <span class="text-emerald-500 font-bold">✔</span> ${ing}
                </li>`;
            });
        } else {
            ingredientsHTML = '<li class="text-gray-400 text-sm">Tidak ada daftar bahan.</li>';
        }
        
        modalBody.innerHTML = `
            <!-- Tab Headers -->
            <div class="flex border-b border-gray-100 mb-6 font-bold text-sm">
                <button onclick="pilihTab('tab-bahan')" id="btn-tab-bahan" class="py-3 px-4 border-b-2 border-emerald-600 text-emerald-700 transition outline-none flex-1 text-center">
                    🥦 Bahan Baku
                </button>
                <button onclick="pilihTab('tab-instruksi')" id="btn-tab-instruksi" class="py-3 px-4 border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition outline-none flex-1 text-center">
                    👨‍🍳 Langkah Memasak
                </button>
            </div>

            <!-- Tab 1: Bahan -->
            <div id="tab-bahan" class="tab-content space-y-4">
                <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100 max-h-96 overflow-y-auto">
                    <ul class="space-y-1">
                        ${ingredientsHTML}
                    </ul>
                </div>
            </div>

            <!-- Tab 2: Instruksi -->
            <div id="tab-instruksi" class="tab-content hidden space-y-4">
                <div class="grid grid-cols-2 gap-4 text-center bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                    <div>
                        <span class="text-xxs text-gray-400 font-bold uppercase tracking-wider block">⏱️ Waktu Masak</span>
                        <span class="font-extrabold text-gray-700 text-sm">${resep.readyInMinutes} menit</span>
                    </div>
                    <div>
                        <span class="text-xxs text-gray-400 font-bold uppercase tracking-wider block">🍽️ Porsi</span>
                        <span class="font-extrabold text-gray-700 text-sm">${resep.servings} porsi</span>
                    </div>
                </div>
                <div class="bg-emerald-50/40 p-5 rounded-2xl border border-emerald-100/50 max-h-96 overflow-y-auto">
                    <p class="text-gray-650 text-sm leading-relaxed whitespace-pre-line">
                        ${resep.instructions}
                    </p>
                </div>
            </div>
        `;
    })
    .catch(err => {
        console.error(err);
        modalBody.innerHTML = '<div class="text-center py-12 text-red-500 font-bold">Gagal mengambil detail resep dari Spoonacular.</div>';
    });
}

function pilihTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(tabId).classList.remove('hidden');
    
    const btnBahan = document.getElementById('btn-tab-bahan');
    const btnInstruksi = document.getElementById('btn-tab-instruksi');
    
    if (tabId === 'tab-bahan') {
        btnBahan.className = "py-3 px-4 border-b-2 border-emerald-600 text-emerald-700 font-bold transition outline-none flex-1 text-center";
        btnInstruksi.className = "py-3 px-4 border-b-2 border-transparent text-gray-400 hover:text-gray-600 font-bold transition outline-none flex-1 text-center";
    } else {
        btnBahan.className = "py-3 px-4 border-b-2 border-transparent text-gray-400 hover:text-gray-600 font-bold transition outline-none flex-1 text-center";
        btnInstruksi.className = "py-3 px-4 border-b-2 border-emerald-600 text-emerald-700 font-bold transition outline-none flex-1 text-center";
    }
}

function tutupModal() {
    document.getElementById('recipe-modal').classList.add('hidden');
    currentRecipeData = null;
}
</script>

@endsection
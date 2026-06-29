@extends('layouts.app')

@section('content')

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 animate-slide-up">
    <div>
        <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2 inline-block">
            Penyimpanan Digital
        </span>
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">
            Isi Kulkas Saya 🧊
        </h2>
    </div>
    <div class="flex gap-2 w-full md:w-auto">
        <button onclick="kosongkanKulkas()" class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-3 rounded-2xl font-bold shadow hover:shadow-rose-500/20 transition active:scale-95 text-xs flex-1 md:flex-initial flex items-center justify-center gap-1.5">
            🗑️ Kosongkan Kulkas
        </button>
        <button onclick="tambahBahan()" class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-2xl font-bold shadow hover:shadow-green-500/20 transition active:scale-95 text-xs flex-1 md:flex-initial flex items-center justify-center gap-1.5">
            ➕ Tambah Bahan
        </button>
    </div>
</div>

<div class="bg-white/80 backdrop-blur-md p-4 rounded-2xl border border-gray-150 shadow-sm flex items-center mb-8 animate-fade-in">
    <input
        type="text"
        id="input-bahan"
        placeholder="Ketik nama bahan baku masakan Anda (contoh: egg, tomato, chicken, milk)..."
        class="w-full bg-transparent p-3 outline-none font-medium text-gray-700 placeholder-gray-400"
        onkeydown="if(event.key === 'Enter') tambahBahan()"
    />
</div>

<div id="daftar-kulkas" class="space-y-6">
    <div class="text-gray-400 text-center py-12 col-span-full">Memuat data kulkas...</div>
</div>

<script>
// Dynamic emoji dictionary
const emojiMap = {
    'garlic': '🧄', 'onion': '🧅', 'tomato': '🍅', 'carrot': '🥕', 'potato': '🥔', 
    'cucumber': '🥒', 'pepper': '🫑', 'chili': '🌶️', 'spinach': '🥬', 'broccoli': '🥦', 
    'mushroom': '🍄', 'corn': '🌽', 'lemon': '🍋', 'ginger': '🫚',
    'chicken': '🍗', 'beef': '🥩', 'pork': '🥓', 'fish': '🐟', 'shrimp': '🍤', 
    'meat': '🍖', 'sausage': '🌭', 'egg': '🥚', 'eggs': '🥚', 'milk': '🥛', 
    'cheese': '🧀', 'butter': '🧈', 'yogurt': '🥛', 'apple': '🍎', 'banana': '🍌', 
    'orange': '🍊', 'grape': '🍇', 'strawberry': '🍓', 'mango': '🥭', 'avocado': '🥑', 
    'pineapple': '🍍', 'watermelon': '🍉', 'rice': '🍚', 'bread': '🍞', 'flour': '🌾', 
    'oil': '🛢️', 'salt': '🧂', 'sugar': '🧂', 'honey': '🍯', 'water': '💧', 
    'noodle': '🍜', 'pasta': '🍝'
};

function getEmoji(name) {
    const cleanName = name.toLowerCase().trim();
    for (const [key, emoji] of Object.entries(emojiMap)) {
        if (cleanName.includes(key)) {
            return emoji;
        }
    }
    return '🍏'; // Default green apple
}

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
    muatDataKulkas();
});

// 1. Mengambil data dari API Backend dan mengelompokkan ke visual rak kulkas
function muatDataKulkas() {
    const container = document.getElementById('daftar-kulkas');
    
    fetch('/api/v1/fridge', {
        headers: globalHeaders
    }) 
    .then(response => {
        if (!response.ok) throw new Error('Gagal memuat kulkas');
        return response.json();
    })
    .then(res => {
        const ingredients = res.data?.ingredients || [];
        container.innerHTML = '';

        if(ingredients.length === 0) {
            container.innerHTML = `
                <div class="text-center py-16 bg-white rounded-3xl border border-gray-100 shadow-sm p-8 animate-fade-in">
                    <span class="text-6xl block mb-3 animate-bounce-slow">❄️</span>
                    <h3 class="font-extrabold text-gray-700 text-lg mb-1">Kulkas Anda Masih Kosong</h3>
                    <p class="text-gray-400 font-medium text-sm">Silakan ketik nama bahan makanan di atas untuk mengisi stok dapur Anda!</p>
                </div>`;
            return;
        }

        let shelfVeg = '';
        let shelfProtein = '';
        let shelfOther = '';

        ingredients.forEach(item => {
            const cleanName = item.name.toLowerCase().trim();
            const emoji = getEmoji(item.name);
            
            const cardHTML = `
                <div class="bg-white p-4 rounded-2xl border border-gray-100/60 shadow-sm flex justify-between items-center transition hover:shadow-md hover:scale-[1.02] active:scale-95 animate-fade-in">
                    <span class="font-bold text-gray-750 flex items-center gap-3">
                        <span class="text-2xl">${emoji}</span> ${item.name}
                    </span>
                    <button onclick="hapusBahan(${item.id})" class="text-rose-500 hover:bg-rose-50 p-2 rounded-xl transition font-extrabold text-xs">
                        Hapus
                    </button>
                </div>`;

            if (cleanName.includes('chicken') || cleanName.includes('beef') || cleanName.includes('meat') || 
                cleanName.includes('fish') || cleanName.includes('egg') || cleanName.includes('shrimp') || 
                cleanName.includes('sausage') || cleanName.includes('pork') || cleanName.includes('bacon')) {
                shelfProtein += cardHTML;
            } else if (cleanName.includes('onion') || cleanName.includes('garlic') || cleanName.includes('tomato') || 
                       cleanName.includes('carrot') || cleanName.includes('spinach') || cleanName.includes('chili') || 
                       cleanName.includes('potato') || cleanName.includes('broccoli') || cleanName.includes('apple') || 
                       cleanName.includes('lemon') || cleanName.includes('avocado') || cleanName.includes('fruit') || 
                       cleanName.includes('veg') || cleanName.includes('ginger')) {
                shelfVeg += cardHTML;
            } else {
                shelfOther += cardHTML;
            }
        });

        let finalHTML = '';
        if (shelfProtein) {
            finalHTML += `
                <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/40 shadow-sm mb-6">
                    <h4 class="font-black text-gray-500 text-xs mb-4 uppercase tracking-wider flex items-center gap-2">🥩 Rak Protein & Daging</h4>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">${shelfProtein}</div>
                </div>`;
        }
        if (shelfVeg) {
            finalHTML += `
                <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/40 shadow-sm mb-6">
                    <h4 class="font-black text-gray-500 text-xs mb-4 uppercase tracking-wider flex items-center gap-2">🥦 Rak Sayuran & Buah</h4>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">${shelfVeg}</div>
                </div>`;
        }
        if (shelfOther) {
            finalHTML += `
                <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/40 shadow-sm">
                    <h4 class="font-black text-gray-500 text-xs mb-4 uppercase tracking-wider flex items-center gap-2">🥛 Rak Dairy & Bahan Lainnya</h4>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">${shelfOther}</div>
                </div>`;
        }

        container.innerHTML = finalHTML;
    })
    .catch(err => {
        console.error(err);
        container.innerHTML = '<div class="text-red-500 text-center py-12 bg-white rounded-3xl shadow">Gagal terhubung ke server API. Pastikan server lokal Anda aktif.</div>';
    });
}

// 2. Mengirim data bahan baru
function tambahBahan() {
    const inputElement = document.getElementById('input-bahan');
    const namaBahan = inputElement.value.trim();

    if (!namaBahan) {
        window.showToast('Silakan ketik nama bahan makanan terlebih dahulu!', 'error');
        return;
    }

    fetch('/api/v1/fridge', {
        method: 'POST',
        headers: globalHeaders,
        body: JSON.stringify({ name: namaBahan })
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal menambah data');
        return response.json();
    })
    .then(() => {
        const emoji = getEmoji(namaBahan);
        window.showToast(`Berhasil menambahkan ${namaBahan} ${emoji} ke kulkas!`);
        inputElement.value = ''; 
        muatDataKulkas();       
    })
    .catch(err => window.showToast('Gagal menyimpan bahan baku baru.', 'error'));
}

// 3. Menghapus item bahan
function hapusBahan(id) {
    window.showConfirmModal(
        'Hapus Bahan Kulkas',
        'Apakah kamu yakin ingin menghapus bahan ini dari kulkas?',
        function() {
            fetch(`/api/v1/fridge/${id}`, {
                method: 'DELETE',
                headers: globalHeaders
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal menghapus data');
                return response.json();
            })
            .then(() => {
                window.showToast('Bahan berhasil dihapus dari kulkas.');
                muatDataKulkas(); 
            })
            .catch(err => window.showToast('Gagal menghapus bahan.', 'error'));
        },
        '🗑️'
    );
}

// 4. Mengosongkan Kulkas
function kosongkanKulkas() {
    window.showConfirmModal(
        'Kosongkan Kulkas Chef',
        'Apakah kamu yakin ingin mengosongkan seluruh isi kulkas? Semua stok makanan akan dihapus.',
        function() {
            fetch('/api/v1/fridge/clear', {
                method: 'DELETE',
                headers: globalHeaders
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal mengosongkan kulkas');
                return response.json();
            })
            .then(() => {
                window.showToast('Kulkas berhasil dikosongkan! ❄️');
                muatDataKulkas();
            })
            .catch(err => window.showToast('Gagal mengosongkan kulkas. Kulkas mungkin sudah kosong.', 'error'));
        },
        '❄️'
    );
}
</script>

@endsection
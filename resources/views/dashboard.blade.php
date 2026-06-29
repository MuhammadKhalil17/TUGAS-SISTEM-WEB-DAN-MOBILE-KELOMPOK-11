@extends('layouts.app')

@section('content')

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-teal-700 via-emerald-600 to-amber-500/80 rounded-3xl p-8 md:p-10 shadow-lg text-white mb-8 relative overflow-hidden animate-slide-up">
    <div class="relative z-10">
        <span class="bg-white/20 text-white text-xxs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-3 inline-block">
            Culinary Hub Kelompok 11
        </span>
        <h2 class="text-3xl md:text-4xl font-black mb-2 flex items-center gap-2">
            Selamat Datang, Chef <span id="chef-name" class="text-amber-100">Loading...</span>! 👨‍🍳
        </h2>
        <p class="text-emerald-50 font-medium max-w-xl text-sm md:text-base mb-4">
            Dapur digital Anda siap! Mari kumpulkan stok bahan kulkas Anda dan buat hidangan bintang lima hari ini.
        </p>
        
        <!-- Gamified Rank Badge -->
        <div class="flex flex-wrap items-center gap-2">
            <span class="bg-black/15 text-white text-xs font-semibold px-3 py-1.5 rounded-xl">
                Gelar Kuliner:
            </span>
            <span class="bg-white/10 border border-white/25 text-white text-xs font-black px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-sm animate-pulse">
                <span id="rank-icon">👶</span> <span id="rank-name" class="tracking-wide">Chef Magang (Apprentice)</span>
            </span>
        </div>
    </div>
    <!-- Decorative background elements -->
    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-amber-400/20 rounded-full blur-2xl"></div>
    <div class="absolute right-1/4 -top-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
</div>

<!-- Stats Grid -->
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 animate-fade-in">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition">
        <div>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Bahan Kulkas</p>
            <h1 id="total-bahan" class="text-4xl font-extrabold text-gray-800 mt-2">0</h1>
            <span class="text-emerald-600 text-xs font-semibold mt-1 inline-block">Siap diolah di wajan</span>
        </div>
        <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-3xl shadow-inner">🥦</div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition">
        <div>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Buku Resep Favorit</p>
            <h1 id="resep-favorit" class="text-4xl font-extrabold text-gray-800 mt-2">0</h1>
            <span class="text-amber-500 text-xs font-semibold mt-1 inline-block">Hidangan pilihan Chef</span>
        </div>
        <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-3xl shadow-inner">❤️</div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition col-span-full lg:col-span-1">
        <div>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Status Dapur</p>
            <h1 class="text-4xl font-extrabold text-teal-600 mt-2">Aktif</h1>
            <span class="text-teal-600 text-xs font-semibold mt-1 inline-block">Semua Sistem Stabil</span>
        </div>
        <div class="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center text-3xl shadow-inner">⚡</div>
    </div>
</div>

<div class="grid md:grid-cols-3 gap-6 mb-8 animate-fade-in" style="animation-delay: 0.1s">
    <!-- Cooking Tip Card -->
    <div class="bg-amber-50/50 border border-amber-100 rounded-3xl p-6 md:col-span-2 flex gap-4 items-start shadow-sm">
        <span class="text-3xl">💡</span>
        <div>
            <h4 class="font-bold text-amber-800 mb-1 text-base">Tips Kuliner Hari Ini:</h4>
            <p id="cooking-tip" class="text-amber-700 text-sm leading-relaxed">
                Memuat tips dapur terbaik untuk Anda...
            </p>
        </div>
    </div>

    <!-- Quick Navigation Link Card -->
    <div class="bg-white rounded-3xl border border-gray-100 p-6 flex flex-col justify-between shadow-sm">
        <h4 class="font-bold text-gray-800 mb-3 text-base">Navigasi Kuliner</h4>
        <div class="flex gap-2">
            <a href="/fridge" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl text-xs text-center transition flex-1 active:scale-95 shadow">
                Kulkas Chef
            </a>
            <a href="/recipes" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl text-xs text-center transition flex-1 active:scale-95 shadow">
                Cari Resep
            </a>
        </div>
    </div>
</div>

<script>
const tips = [
    "Simpan apel bersama kentang untuk menjaga kentang tetap segar dan mencegah bertunas lebih cepat.",
    "Potong bawang bombay di dekat aliran air dingin atau nyalakan lilin di dekat talenan untuk mengurangi rasa pedih di mata.",
    "Rendam sayuran layu di dalam mangkuk air dingin berisi es batu selama 15 menit untuk mengembalikannya menjadi renyah.",
    "Untuk mengetes kesegaran telur, masukkan ke dalam segelas air. Telur segar akan tenggelam mendatar, sedangkan telur lama akan melayang.",
    "Beri sedikit minyak goreng pada pisau sebelum memotong bahan lengket seperti cabai atau bawang putih agar mudah dibersihkan."
];

document.addEventListener("DOMContentLoaded", function() {
    // 1. Dapatkan nama chef
    const user = JSON.parse(localStorage.getItem('auth_user') || '{}');
    document.getElementById('chef-name').innerText = user.name || 'User';

    // 2. Pilih tips acak
    const randomTip = tips[Math.floor(Math.random() * tips.length)];
    document.getElementById('cooking-tip').innerText = randomTip;

    // 3. Load stats
    const activeToken = localStorage.getItem('auth_token');
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    };
    if (activeToken) {
        headers['Authorization'] = `Bearer ${activeToken}`;
    }

    let totalBahan = 0;
    let totalFavorit = 0;

    function updateChefRank() {
        const points = totalBahan + (totalFavorit * 2);
        let rankName = "Chef Magang (Apprentice) 👶";
        let rankIcon = "👶";
        
        if (points >= 1 && points <= 4) {
            rankName = "Commis Chef (Asisten) 🍳";
            rankIcon = "🍳";
        } else if (points >= 5 && points <= 9) {
            rankName = "Chef de Partie (Spesialis) 👨‍🍳";
            rankIcon = "👨‍🍳";
        } else if (points >= 10) {
            rankName = "Executive Chef (Legendaris) 👑";
            rankIcon = "👑";
        }
        
        document.getElementById('rank-icon').innerText = rankIcon;
        document.getElementById('rank-name').innerText = rankName;
    }

    // Ambil data Total Bahan dari FridgeController melalui API
    fetch('/api/v1/fridge', { headers })
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengambil data kulkas');
            return response.json();
        })
        .then(data => {
            totalBahan = (data && data.data && data.data.ingredients) ? data.data.ingredients.length : 
                          (data && data.ingredients ? data.ingredients.length : (Array.isArray(data) ? data.length : 0));
            document.getElementById('total-bahan').innerText = totalBahan;
            updateChefRank();
        })
        .catch(err => console.error("Error Kulkas:", err));

    // Ambil data Resep Favorit dari FavoriteRecipeController melalui API
    fetch('/api/v1/favorite-recipes', { headers })
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengambil data favorit');
            return response.json();
        })
        .then(data => {
            totalFavorit = (data && data.data) ? data.data.length : (Array.isArray(data) ? data.length : 0);
            document.getElementById('resep-favorit').innerText = totalFavorit;
            updateChefRank();
        })
        .catch(err => console.error("Error Favorit:", err));
});
</script>

@endsection
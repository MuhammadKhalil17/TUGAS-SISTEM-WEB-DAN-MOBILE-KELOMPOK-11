@extends('layouts.app')

@section('content')

<div class="flex justify-between mb-6">
    <h2 class="text-3xl font-bold">
        Isi Kulkas
    </h2>
    <button onclick="tambahBahan()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
        Tambah Bahan
    </button>
</div>

<input
    type="text"
    id=\"input-bahan\"
    placeholder="Ketik nama bahan (contoh: garlic, chicken)..."
    class="w-full border p-3 rounded mb-5 shadow-sm"
/>

<div id="daftar-kulkas" class="space-y-4">
    <div class="text-gray-500 text-center py-4">Memuat data kulkas...</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    muatDataKulkas(); // Memanggil fungsi yang benar saat halaman dimuat
});

// 1. Mengambil data dari API Backend
function muatDataKulkas() {
    const container = document.getElementById('daftar-kulkas');
    
    fetch('/api/v1/fridge') 
        .then(response => response.json())
        .then(res => {
            const ingredients = res.data?.ingredients || [];
            container.innerHTML = '';

            if(ingredients.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">Kulkas kosong. Silakan tambah bahan!</p>';
                return;
            }

            ingredients.forEach(item => {
                container.innerHTML += `
                    <div class="bg-white p-4 rounded shadow flex justify-between items-center">
                        <span class="font-medium text-gray-700">🍏 ${item.name}</span>
                        <button onclick="hapusBahan(${item.id})" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition font-bold text-sm">Hapus</button>
                    </div>`;
            });
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = '<p class="text-red-500 text-center py-4">Gagal terhubung ke server API</p>';
        });
}

// 2. Mengirim data bahan baru
function tambahBahan() {
    const inputElement = document.getElementById('input-bahan');
    const namaBahan = inputElement.value.trim();

    if (!namaBahan) {
        alert('Silakan ketik nama bahan makanan terlebih dahulu!');
        return;
    }

    fetch('/api/v1/fridge', { // Diubah ke /fridge
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ name: namaBahan })
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal menambah data');
        return response.json();
    })
    .then(() => {
        inputElement.value = ''; 
        muatDataKulkas();       
    })
    .catch(err => alert('Gagal menyimpan bahan baku baru.'));
}

// 3. Menghapus item bahan
function hapusBahan(id) {
    if (!confirm('Apakah kamu yakin ingin menghapus bahan ini?')) return;

    fetch(`/api/v1/fridge/${id}`, { // Diubah ke /fridge
        method: 'DELETE',
        headers: { 'Accept': 'application/json' }
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal menghapus data');
        return response.json();
    })
    .then(() => {
        muatDataKulkas(); 
    })
    .catch(err => alert('Gagal menghapus bahan.'));
}
</script>

@endsection
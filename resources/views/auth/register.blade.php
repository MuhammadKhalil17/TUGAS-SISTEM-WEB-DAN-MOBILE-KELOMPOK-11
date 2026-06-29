@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center py-12">
    <div class="w-full max-w-md bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-xl border border-white/20 animate-fade-in">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-green-600 tracking-tight flex items-center justify-center gap-2 mb-2">
                🥬 Kulkasku
            </h1>
            <p class="text-gray-500">Mulai daftarkan akun baru Anda</p>
        </div>

        <div id="alert-error" class="hidden bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm" role="alert">
            <span id="error-message"></span>
        </div>

        <div id="alert-success" class="hidden bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm" role="alert">
            Registrasi berhasil! Mengalihkan ke login...
        </div>

        <form onsubmit="handleRegister(event)" class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" id="name" required placeholder="Nama Lengkap" class="w-full border-gray-200 border p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" />
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" id="email" required placeholder="nama@email.com" class="w-full border-gray-200 border p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" id="password" required placeholder="••••••••" class="w-full border-gray-200 border p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" />
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg hover:shadow-green-500/20 active:scale-95">
                Daftar Akun
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            Sudah punya akun? 
            <a href="/login" class="text-green-600 font-semibold hover:underline">Masuk disini</a>
        </p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    if (localStorage.getItem('auth_token')) {
        window.location.href = '/dashboard';
    }
});

function handleRegister(e) {
    e.preventDefault();
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const errorAlert = document.getElementById('alert-error');
    const errorMessage = document.getElementById('error-message');
    const successAlert = document.getElementById('alert-success');

    errorAlert.classList.add('hidden');
    successAlert.classList.add('hidden');

    fetch('/api/v1/auth/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ name, email, password })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw new Error(err.message || 'Registrasi gagal. Email mungkin sudah terdaftar.'); });
        }
        return response.json();
    })
    .then(res => {
        successAlert.classList.remove('hidden');
        setTimeout(() => {
            window.location.href = '/login';
        }, 1500);
    })
    .catch(err => {
        errorMessage.innerText = err.message;
        errorAlert.classList.remove('hidden');
    });
}
</script>
@endsection

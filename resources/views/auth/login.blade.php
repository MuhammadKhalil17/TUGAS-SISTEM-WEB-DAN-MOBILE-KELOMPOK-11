@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center py-12">
    <div class="w-full max-w-md bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-xl border border-white/20 animate-fade-in">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-green-600 tracking-tight flex items-center justify-center gap-2 mb-2">
                🥬 Kulkasku
            </h1>
            <p class="text-gray-500">Kelola kulkas digital & resep masakanmu dengan mudah</p>
        </div>

        <div id="alert-error" class="hidden bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm" role="alert">
            <span id="error-message"></span>
        </div>

        <form id="form-login" onsubmit="handleLogin(event)" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" id="email" required placeholder="nama@email.com" class="w-full border-gray-200 border p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" id="password" required placeholder="••••••••" class="w-full border-gray-200 border p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" />
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg hover:shadow-green-500/20 active:scale-95">
                Masuk
            </button>
        </form>

        <div class="relative flex py-5 items-center">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="flex-shrink mx-4 text-gray-400 text-sm">atau</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <button onclick="loginAsTestUser()" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg hover:shadow-emerald-500/20 active:scale-95 mb-6 flex items-center justify-center gap-2">
            💡 Masuk sebagai Akun Demo
        </button>

        <p class="text-center text-sm text-gray-600">
            Belum punya akun? 
            <a href="/register" class="text-green-600 font-semibold hover:underline">Daftar sekarang</a>
        </p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Jika sudah login, langsung ke dashboard
    if (localStorage.getItem('auth_token')) {
        window.location.href = '/dashboard';
    }
});

function handleLogin(e) {
    e.preventDefault();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const errorAlert = document.getElementById('alert-error');
    const errorMessage = document.getElementById('error-message');

    errorAlert.classList.add('hidden');

    fetch('/api/v1/auth/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email, password })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw new Error(err.message || 'Email atau password salah'); });
        }
        return response.json();
    })
    .then(res => {
        if (res.status === 'success' && res.data?.token) {
            localStorage.setItem('auth_token', res.data.token);
            localStorage.setItem('auth_user', JSON.stringify(res.data.user));
            window.location.href = '/dashboard';
        } else {
            throw new Error('Gagal memproses login dari server.');
        }
    })
    .catch(err => {
        errorMessage.innerText = err.message;
        errorAlert.classList.remove('hidden');
    });
}

function loginAsTestUser() {
    document.getElementById('email').value = 'test@example.com';
    document.getElementById('password').value = 'password';
    document.getElementById('form-login').dispatchEvent(new Event('submit', { cancelable: true }));
}
</script>
@endsection

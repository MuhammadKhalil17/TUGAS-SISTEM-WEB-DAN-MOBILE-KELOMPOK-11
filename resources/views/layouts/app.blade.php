<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kulkasku — Solusi Masak Pintar</title>

    <!-- Favicon emoji -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🥬</text></svg>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Outfit', sans-serif; }

        /* Mobile menu animation */
        #mobile-menu {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.3s ease;
        }
        #mobile-menu.open {
            grid-template-rows: 1fr;
        }
        #mobile-menu > div {
            overflow: hidden;
        }

        /* Hamburger animation */
        .ham-bar { transition: all 0.25s ease; transform-origin: center; }
        #hamburger.open .bar1 { transform: translateY(7px) rotate(45deg); }
        #hamburger.open .bar2 { opacity: 0; transform: scaleX(0); }
        #hamburger.open .bar3 { transform: translateY(-7px) rotate(-45deg); }
    </style>

    <script>
        // Auth Guard
        const token = localStorage.getItem('auth_token');
        const path = window.location.pathname;
        if (!token && path !== '/login' && path !== '/register') {
            window.location.href = '/login';
        }
    </script>
</head>

<body class="foodie-bg min-h-screen flex flex-col">

<nav class="kitchen-gradient shadow-md border-b border-teal-800/30 sticky top-0 z-40 backdrop-blur-md">
    <div class="container mx-auto px-4 sm:px-6">
        <!-- Main bar -->
        <div class="flex justify-between items-center h-14 sm:h-16">

            <!-- Logo -->
            <a href="/dashboard" class="flex items-center gap-2 flex-shrink-0">
                <span class="text-2xl animate-bounce-slow">🥬</span>
                <span class="text-white text-xl font-extrabold tracking-tight">Kulkasku</span>
            </a>

            <!-- Desktop Nav Links — only shown by JS on md+ when logged in -->
            <div id="nav-links" class="hidden items-center space-x-1">
                <a href="/dashboard" class="text-emerald-100 hover:text-white hover:bg-white/10 px-3 py-2 rounded-xl font-medium transition active:scale-95 text-sm whitespace-nowrap">Dashboard</a>
                <a href="/fridge" class="text-emerald-100 hover:text-white hover:bg-white/10 px-3 py-2 rounded-xl font-medium transition active:scale-95 text-sm whitespace-nowrap">Kulkas Saya</a>
                <a href="/recipes" class="text-emerald-100 hover:text-white hover:bg-white/10 px-3 py-2 rounded-xl font-medium transition active:scale-95 text-sm whitespace-nowrap">Rekomendasi Resep</a>
                <a href="/favorites" class="text-emerald-100 hover:text-white hover:bg-white/10 px-3 py-2 rounded-xl font-medium transition active:scale-95 text-sm whitespace-nowrap">Resep Favorit</a>
            </div>

            <!-- Right side actions -->
            <div class="flex items-center gap-2">
                <!-- Desktop: User Profile — only shown by JS on md+ when logged in -->
                <div id="user-profile" class="hidden items-center gap-2 text-white">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm flex-shrink-0" id="user-avatar">U</div>
                    <span class="font-medium text-sm hidden lg:inline" id="user-name">Loading...</span>
                    <button onclick="handleLogout()" class="ml-1 bg-rose-500 hover:bg-rose-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow transition active:scale-95 whitespace-nowrap">Keluar</button>
                </div>

                <!-- Desktop: Auth Buttons — only shown by JS on md+ when NOT logged in -->
                <div id="auth-buttons" class="hidden items-center space-x-2">
                    <a href="/login" class="text-white hover:underline text-sm font-medium">Masuk</a>
                    <a href="/register" class="bg-white text-emerald-700 px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-emerald-50 transition active:scale-95">Daftar</a>
                </div>

                <!-- Hamburger — mobile only, always visible -->
                <button id="hamburger"
                    class="md:hidden flex flex-col justify-center items-center w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 transition active:scale-95 flex-shrink-0"
                    onclick="toggleMobileMenu()"
                    aria-label="Buka menu">
                    <span class="ham-bar bar1 block w-5 h-0.5 bg-white mb-1.5 rounded-full"></span>
                    <span class="ham-bar bar2 block w-5 h-0.5 bg-white mb-1.5 rounded-full"></span>
                    <span class="ham-bar bar3 block w-5 h-0.5 bg-white rounded-full"></span>
                </button>
            </div>
        </div>

        <!-- Mobile Dropdown Menu (hidden on md+) -->
        <div id="mobile-menu" class="md:hidden">
            <div>
                <!-- Logged-in mobile content -->
                <div id="mobile-nav-links" class="hidden py-3 border-t border-white/10">
                    <!-- Mobile user info -->
                    <div class="flex items-center gap-3 px-2 py-2.5 mb-2 bg-white/10 rounded-2xl">
                        <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm text-white flex-shrink-0" id="mobile-user-avatar">U</div>
                        <div class="min-w-0">
                            <div class="text-white text-sm font-bold truncate" id="mobile-user-name">Loading...</div>
                            <div class="text-emerald-200 text-xs">Chef Aktif ✅</div>
                        </div>
                    </div>
                    <!-- Mobile links -->
                    <nav class="space-y-0.5">
                        <a href="/dashboard" class="flex items-center gap-3 text-emerald-100 hover:text-white hover:bg-white/10 px-3 py-2.5 rounded-xl font-medium transition text-sm">📊 Dashboard</a>
                        <a href="/fridge" class="flex items-center gap-3 text-emerald-100 hover:text-white hover:bg-white/10 px-3 py-2.5 rounded-xl font-medium transition text-sm">🧊 Kulkas Saya</a>
                        <a href="/recipes" class="flex items-center gap-3 text-emerald-100 hover:text-white hover:bg-white/10 px-3 py-2.5 rounded-xl font-medium transition text-sm">🍳 Rekomendasi Resep</a>
                        <a href="/favorites" class="flex items-center gap-3 text-emerald-100 hover:text-white hover:bg-white/10 px-3 py-2.5 rounded-xl font-medium transition text-sm">❤️ Resep Favorit</a>
                    </nav>
                    <button onclick="handleLogout()" class="mt-2 w-full bg-rose-500 hover:bg-rose-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow transition active:scale-95">
                        🚪 Keluar
                    </button>
                </div>

                <!-- Logged-out mobile content -->
                <div id="mobile-auth-buttons" class="hidden py-3 border-t border-white/10">
                    <div class="flex gap-2">
                        <a href="/login" class="flex-1 text-center bg-white/10 hover:bg-white/20 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition">Masuk</a>
                        <a href="/register" class="flex-1 text-center bg-white text-emerald-700 text-sm font-bold px-4 py-2.5 rounded-xl shadow hover:bg-emerald-50 transition">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<main class="container mx-auto px-4 sm:px-6 py-4 sm:py-6 flex-grow">
    @yield('content')
</main>

<!-- Toast -->
<div id="toast-container" class="fixed top-4 right-4 z-[60] flex flex-col gap-2 pointer-events-none" style="max-width: min(calc(100vw - 2rem), 20rem)"></div>

<!-- Confirm Modal -->
<div id="confirm-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-[55] flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-gray-100 flex flex-col text-center">
        <span class="text-5xl block mb-2" id="confirm-modal-icon">🍳</span>
        <h3 id="confirm-modal-title" class="font-black text-xl text-gray-800 tracking-tight mb-2">Konfirmasi Chef</h3>
        <p id="confirm-modal-text" class="text-gray-400 text-sm font-medium mb-6">Apakah Anda yakin dengan pilihan ini?</p>
        <div class="flex gap-3">
            <button id="confirm-modal-cancel" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2.5 px-4 rounded-xl text-xs flex-1 transition active:scale-95">Batal</button>
            <button id="confirm-modal-ok" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs flex-1 transition active:scale-95 shadow">Ya, Konfirmasi</button>
        </div>
    </div>
</div>

<footer class="bg-white border-t border-gray-100 py-5 mt-8">
    <div class="container mx-auto px-4 sm:px-6 text-center text-gray-400 text-xs sm:text-sm">
        &copy; 2026 Kulkasku Kelompok 11. Dibuat dengan ❤️ untuk Web &amp; Mobile System.
    </div>
</footer>

<script>
// ── Mobile Menu Toggle ────────────────────────────────
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    const btn = document.getElementById('hamburger');
    menu.classList.toggle('open');
    btn.classList.toggle('open');
}
// Close menu on outside click
document.addEventListener('click', function(e) {
    const menu = document.getElementById('mobile-menu');
    const btn = document.getElementById('hamburger');
    if (menu.classList.contains('open') && !menu.contains(e.target) && !btn.contains(e.target)) {
        menu.classList.remove('open');
        btn.classList.remove('open');
    }
});

// ── Toast ─────────────────────────────────────────────
window.showToast = function(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const isSuccess = type === 'success';
    toast.className = [
        'p-3.5 rounded-2xl shadow-lg border text-sm font-semibold pointer-events-auto flex items-center gap-2 transition duration-300',
        isSuccess ? 'text-emerald-800 border-emerald-100 bg-emerald-50' : 'text-rose-800 border-rose-100 bg-rose-50'
    ].join(' ');
    toast.innerHTML = `<span>${isSuccess ? '🟢' : '🔴'}</span><span class="leading-snug">${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3500);
};

// ── Confirm Modal ─────────────────────────────────────
window.showConfirmModal = function(title, text, onConfirm, icon = '👨‍🍳') {
    const modal = document.getElementById('confirm-modal');
    document.getElementById('confirm-modal-title').innerText = title;
    document.getElementById('confirm-modal-text').innerText = text;
    document.getElementById('confirm-modal-icon').innerText = icon;
    modal.classList.remove('hidden');
    const cancel = document.getElementById('confirm-modal-cancel').cloneNode(true);
    const ok = document.getElementById('confirm-modal-ok').cloneNode(true);
    document.getElementById('confirm-modal-cancel').replaceWith(cancel);
    document.getElementById('confirm-modal-ok').replaceWith(ok);
    cancel.onclick = () => modal.classList.add('hidden');
    ok.onclick = () => { modal.classList.add('hidden'); if (typeof onConfirm === 'function') onConfirm(); };
};

// ── Auth State ────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    const isLoggedIn = !!localStorage.getItem('auth_token');
    const user = JSON.parse(localStorage.getItem('auth_user') || '{}');
    const isMd = window.matchMedia('(min-width: 768px)').matches;

    if (isLoggedIn) {
        // Desktop — show nav & profile only on md+ screens
        if (isMd) {
            document.getElementById('nav-links').style.display = 'flex';
            document.getElementById('user-profile').style.display = 'flex';
        }
        // Mobile — show mobile nav
        document.getElementById('mobile-nav-links').classList.remove('hidden');

        if (user.name) {
            document.getElementById('user-name').innerText = user.name;
            document.getElementById('user-avatar').innerText = user.name.charAt(0).toUpperCase();
            document.getElementById('mobile-user-name').innerText = user.name;
            document.getElementById('mobile-user-avatar').innerText = user.name.charAt(0).toUpperCase();
        }

        // Highlight active link — desktop
        const currentPath = window.location.pathname;
        document.querySelectorAll('#nav-links a').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.className = 'text-white bg-black/15 px-3 py-2 rounded-xl font-bold shadow-inner transition text-sm whitespace-nowrap';
            }
        });
        // Highlight active link — mobile
        document.querySelectorAll('#mobile-nav-links a').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.className = 'flex items-center gap-3 text-white bg-white/15 px-3 py-2.5 rounded-xl font-bold transition text-sm shadow-inner';
            }
        });
    } else {
        // Desktop auth buttons
        if (isMd) {
            document.getElementById('auth-buttons').style.display = 'flex';
        }
        // Mobile auth buttons
        document.getElementById('mobile-auth-buttons').classList.remove('hidden');
    }

    // Handle resize — re-apply desktop visibility
    window.addEventListener('resize', function() {
        const nowMd = window.matchMedia('(min-width: 768px)').matches;
        if (isLoggedIn) {
            document.getElementById('nav-links').style.display = nowMd ? 'flex' : 'none';
            document.getElementById('user-profile').style.display = nowMd ? 'flex' : 'none';
        } else {
            document.getElementById('auth-buttons').style.display = nowMd ? 'flex' : 'none';
        }
    });
});

// ── Logout ────────────────────────────────────────────
function handleLogout() {
    window.showConfirmModal('Keluar Kulkasku', 'Apakah kamu yakin ingin keluar?', function() {
        const t = localStorage.getItem('auth_token');
        if (t) fetch('/api/v1/auth/logout', { method: 'POST', headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${t}` } }).catch(() => {});
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
        window.location.href = '/login';
    }, '🚪');
}
</script>
</body>
</html>
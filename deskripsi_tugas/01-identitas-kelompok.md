# Identitas Kelompok

---

**Nama Kelompok:** `kelompok 11`

**Nama Proyek / Aplikasi:** `Recipe Generator via Catatan Isi Kulkas/Kulkasku`

**Jumlah Anggota:** `3` orang

**Repositori:** `...`

---

## Anggota & Role

**Anggota 1**
- Nama Lengkap: `Muhammad Raja Hazmi`
- NIM: `230705151`
- Role: `Backend`
- Teknologi: `Laravel 12`

**Anggota 2**
- Nama Lengkap: `Muhammad Khalil`
- NIM: `230705213`
- Role: `DevOps`
- Teknologi: `GitHub Actions & Railway / Vercel`

**Anggota 3**
- Nama Lengkap: `Khairul Amri`
- NIM: `230705190`
- Role: `Frontend`
- Teknologi: `React.js (Vite) & Tailwind CSS`
---

## Stack Teknologi

**Frontend:** `React.js (Vite) & Tailwind CSS`
*(Menggunakan Vite agar ringan dan cepat saat development, serta Tailwind CSS untuk mempercepat pembuatan antarmuka yang responsif)*

**Backend:** `Laravel 12`
*(Menggunakan REST API bawaan Laravel untuk memproses logika bisnis dan menjembatani integrasi dengan third-party API)*

**Database:** `MySQL`
*(Relational database yang andal, sangat kompatibel dengan Eloquent ORM bawaan Laravel, dan mudah dikonfigurasi oleh tim kecil)*

**DevOps / Infrastruktur:** `GitHub Actions & Railway / Vercel`
*(GitHub Actions digunakan untuk otomatisasi pengecekan kode, Vercel untuk mendeploy Frontend React secara gratis, dan Railway untuk mendeploy Backend Laravel + MySQL secara cepat)*

---

## Arsitektur Aplikasi

Proyek ini menerapkan arsitektur berbasis layanan (*service-based*) terpisah. Aplikasi Frontend (React.js) berjalan sepenuhnya di sisi klien dan berkomunikasi dengan Aplikasi Backend (Laravel) menggunakan protokol HTTP melalui REST API. Aplikasi Backend Laravel bertindak sebagai pusat kendali data internal (autentikasi dan penyimpanan data user) sekaligus berfungsi sebagai *secure proxy* untuk melakukan HTTP request ke Third-Party API (Spoonacular) guna mengambil data resep secara real-time tanpa mengekspos API Key ke sisi klien.

**Aplikasi 1 — Frontend**
- Nama Aplikasi: `Kulkasku Web Client`
- Deskripsi Singkat: Aplikasi web antarmuka pengguna yang berfungsi untuk mengelola daftar bahan makanan di kulkas digital pengguna, menampilkan daftar rekomendasi resep makanan, serta mengelola halaman bookmark resep favorit.
- Berkomunikasi dengan: `Aplikasi 2 — Backend (Laravel)` melalui REST API untuk mengirim data masukan bahan baku dan meminta data pengguna.

**Aplikasi 2 — Backend (Laravel)**
- Nama Aplikasi / Service: `Kulkasku Core API Service`
- Deskripsi Singkat: Layanan server utama berbasis Laravel 12 yang menangani enkripsi data, manajemen sesi/autentikasi pengguna, penyimpanan data bahan makanan internal, serta penanganan integrasi data eksternal dengan Spoonacular API.
- Menyediakan layanan untuk: `Aplikasi 1 — Frontend` (menyediakan endpoint CRUD data kulkas dan bookmark) dan bertindak sebagai penghubung ke `Third-Party API (Spoonacular)`.
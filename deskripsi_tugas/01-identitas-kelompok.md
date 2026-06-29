# Identitas Kelompok

---

**Nama Kelompok:** `kelompok 11`

**Nama Proyek / Aplikasi:** `Recipe Generator via Catatan Isi Kulkas/Kulkasku`

**Jumlah Anggota:** `3` orang

**Repositori:** `https://github.com/MuhammadKhalil17/TUGAS-SISTEM-WEB-DAN-MOBILE-KELOMPOK-11`

---

## Anggota & Role

**Anggota 1**
- Nama Lengkap: `Muhammad Raja Hazmi`
- NIM: `230705151`
- Role: `Backend`
- Teknologi: `Laravel 12 (REST API & Auth)`

**Anggota 2**
- Nama Lengkap: `Muhammad Khalil`
- NIM: `230705213`
- Role: `DevOps`
- Teknologi: `Docker, Git, & Railway Cloud Deployment`

**Anggota 3**
- Nama Lengkap: `Khairul Amri`
- NIM: `230705190`
- Role: `Frontend`
- Teknologi: `Laravel Blade, Vanilla JS, & Tailwind CSS v4`
---

## Stack Teknologi

**Frontend:** `Laravel Blade, Vanilla JS, & Tailwind CSS v4`
*(Menggunakan engine template Blade bawaan Laravel agar proses development terintegrasi erat, Vanilla JavaScript untuk fetch API asinkronus, serta Tailwind CSS v4 melalui Vite untuk mempercepat pembuatan antarmuka responsif & premium)*

**Backend:** `Laravel 12 (Core API Services)`
*(Menggunakan API endpoint bawaan Laravel untuk memproses logika bisnis kulkas & bookmark resep, serta bertindak sebagai secure proxy untuk integrasi dengan third-party API)*

**Database:** `MySQL`
*(Relational database yang andal, dideploy di Railway, sangat kompatibel dengan Eloquent ORM bawaan Laravel)*

**DevOps / Infrastruktur:** `Docker & Railway Cloud`
*(Deployment otomatis berbasis Dockerfile terintegrasi Node.js build step untuk meng-compile asset CSS/JS dan di-deploy langsung di platform cloud Railway)*

---

## Arsitektur Aplikasi

Proyek ini menerapkan arsitektur monolitik modular dengan integrasi API asinkronus. Aplikasi Frontend (Laravel Blade + JS) dirender oleh server dan berjalan di sisi klien, kemudian berkomunikasi secara asinkronus ke API internal `/api/v1/` menggunakan protokol HTTP. Layanan API Laravel bertindak sebagai pusat kendali autentikasi dan penyimpanan data user (MySQL) sekaligus berfungsi sebagai *secure proxy* untuk melakukan HTTP request ke Third-Party API (Spoonacular) guna mengambil data resep secara aman tanpa mengekspos API Key ke publik.

**Aplikasi & Service Utama**
- Nama Aplikasi: `Kulkasku Web & API Service`
- Deskripsi Singkat: Aplikasi terpadu berbasis Laravel 12 yang menyediakan visualisasi kulkas digital interaktif, pencarian resep asinkronus ke Spoonacular API, penyimpanan bookmark database lokal, serta manajemen sesi dengan token keamanan.
- Integrasi Layanan: Bertindak sebagai penghubung dan secure middleware ke `Third-Party API (Spoonacular)`.
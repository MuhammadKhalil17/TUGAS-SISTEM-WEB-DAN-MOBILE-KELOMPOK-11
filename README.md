# Kulkasku 🥬 — Sistem Manajemen Kulkas & Generator Resep Pintar

Selamat datang di **Kulkasku**, sebuah aplikasi berbasis Laravel yang dirancang untuk membantu pengguna mengelola bahan masakan di dalam kulkas secara digital dan mendapatkan rekomendasi resep masakan pintar berdasarkan stok yang ada.

Proyek ini dibuat untuk memenuhi tugas mata kuliah **Sistem Web dan Mobile (Kelompok 11)**.

---

## 🌐 Tautan Aplikasi Live (Production)
Aplikasi saat ini aktif dan berjalan online di internet:
**Link URL**: [https://web-production-45d72.up.railway.app](https://web-production-45d72.up.railway.app)

---

## 👨‍🍳 Akun Uji Coba (Lab Assistant / Dosen)

Untuk memudahkan pengujian oleh Asisten Praktikum/Dosen, silakan gunakan akun default berikut yang sudah didaftarkan melalui seeder database:

### Akun 1 (Utama - Asisten Lab)
* **Email:** `asisten@kulkasku.com`
* **Password:** `asisten123`
* **Nama:** `Asisten Laboratorium`

### Akun 2 (Test User)
* **Email:** `test@example.com`
* **Password:** `password`
* **Nama:** `Test User`

---

## 🚀 Fitur Unggulan

1. **Chef Hub Dashboard**: Panel utama dengan sambutan interaktif dan penentuan **Chef Rank** (gelar kuliner) secara dinamis berdasarkan keaktifan bahan dapur Anda. Dilengkapi juga dengan tips memasak harian.
2. **Kulkas Saya (Visual Compartment)**: Tampilan isi kulkas yang dikelompokkan secara estetik ke dalam rak-rak virtual (Protein, Sayur & Buah, Dairy/Lainnya) lengkap dengan deteksi emoji otomatis.
3. **Smart Recipe Generator**: Pencarian resep masakan global (Spoonacular API) yang secara otomatis mencocokkan bahan yang tersedia di kulkas Anda.
4. **Resep Favorit**: Bookmark resep pilihan Anda ke dalam buku resep digital pribadi.
5. **Sistem Modal & Toast Kustom**: Penggunaan modal konfirmasi glassmorphism dan notifikasi toast mengambang yang responsif menggantikan alert bawaan browser.

---

## 🛠️ Panduan Instalasi & Pengujian

Silakan ikuti langkah-langkah berikut untuk menjalankan aplikasi di lingkungan lokal Anda:

### 1. Kloning & Persiapan Environment
Pastikan Anda berada di direktori root proyek, lalu salin berkas konfigurasi environment:
```bash
cp .env.example .env
```
Sesuaikan pengaturan database Anda di berkas `.env` (misalnya koneksi MySQL):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=username_database_anda
DB_PASSWORD=password_database_anda
```

### 2. Instalasi Dependensi Backend (Composer)
```bash
composer install
```
Jangan lupa untuk men-generate application key Laravel:
```bash
php artisan key:generate
```

### 3. Instalasi Dependensi Frontend & Kompilasi Assets (NPM + Vite)
```bash
npm install
npm run build
```

### 4. Migrasi Database & Seeding Akun Uji Coba
Jalankan migrasi tabel beserta seeder untuk memuat akun uji coba di atas:
```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Server Lokal
```bash
php artisan serve
```
Buka peramban (browser) Anda dan akses alamat [http://127.0.0.1:8000](http://127.0.0.1:8000). Anda dapat langsung masuk menggunakan akun asisten praktikum di atas!

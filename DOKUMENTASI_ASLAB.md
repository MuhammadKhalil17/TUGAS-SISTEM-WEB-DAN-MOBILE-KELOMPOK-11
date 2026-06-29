# 🍳 Panduan Pengujian Aplikasi "Kulkasku" (Untuk Asisten Laboratorium)

Selamat datang Asisten Laboratorium! Dokumen ini dirancang untuk memudahkan Anda melakukan pengujian fitur pada aplikasi **Kulkasku** Kelompok 11.

---

## 🌐 Informasi Deployment & Autentikasi

* **Link Aplikasi (Production):** [https://web-production-45d72.up.railway.app](https://web-production-45d72.up.railway.app)
* **Akun Demo / Uji Coba:**
  * **Email:** `asisten@kulkasku.com`
  * **Password:** `asisten123`
  * *(Anda juga dapat mengklik tombol **"💡 Masuk sebagai Akun Demo"** di halaman login untuk masuk secara instan)*

---

## 🚀 Langkah-Langkah Pengujian Fitur Utama

Aplikasi ini memiliki **5 Fitur Utama** yang dapat Anda uji langsung dengan urutan berikut:

### 1. Autentikasi Pengguna (Register & Login)
* **Register:** Masuk ke menu "Daftar", isi nama, email baru, dan password.
* **Login:** Masuk dengan email yang baru dibuat atau gunakan **Akun Demo** instan. Token akan disimpan aman di `localStorage`.

### 2. Manajemen Kulkas Digital (Isi Kulkas Saya)
* Masuk ke menu **"Kulkas Saya"** di navbar.
* **Tambah Bahan:** Masukkan bahan makanan satu per satu di input bar (contoh: `chicken`, `tomato`, `egg`, `milk`).
* **Pengelompokan Rak Otomatis:** Bahan makanan yang Anda masukkan akan dikelompokkan secara pintar berdasarkan kategorinya (Rak Protein, Rak Sayuran/Buah, atau Rak Lainnya) dengan ikon emoji yang relevan.
* **Hapus Bahan:** Anda dapat menghapus bahan spesifik dengan tombol "Hapus" atau mengosongkan kulkas sekaligus menggunakan tombol "Kosongkan Kulkas".

### 3. Rekomendasi Resep Pintar (Spoonacular Third-Party API)
* Masuk ke menu **"Rekomendasi Resep"**.
* Klik tombol **"Temukan Resep Berdasarkan Isi Kulkas"**.
* Sistem akan memproses daftar bahan dari kulkas Anda secara real-time dan mencari kecocokan resep masakan dari API eksternal **Spoonacular**.
* Hasil pencarian akan menampilkan resep masakan lengkap dengan detail **jumlah bahan yang cocok** dan **jumlah bahan yang kurang**.

### 4. Detail Langkah & Cara Memasak (Tab System)
* Pada salah satu resep hasil pencarian, klik tombol **"Cara Masak"**.
* Modal responsif akan muncul dan menampilkan detail dalam bentuk dua tab:
  * **Tab Bahan Baku:** Menampilkan list bahan pendukung yang dibutuhkan.
  * **Tab Langkah Memasak:** Menampilkan instruksi memasak langkah demi langkah beserta estimasi waktu memasak.

### 5. Buku Resep Favorit (Bookmark System)
* Pada resep yang disukai, klik tombol **"❤️ Simpan Favorit"**.
* Buka menu **"Resep Favorit"** di navbar untuk melihat daftar resep yang telah Anda bookmark.
* Data bookmark disimpan secara persisten di database internal (MySQL). Anda juga dapat menghapusnya kapan saja menggunakan tombol "Hapus Favorit".

---

## 🛠️ Ringkasan Stack Teknologi

* **Frontend:** Laravel Blade, TailwindCSS v4, Vanilla JavaScript, Vite Bundler (Fully Responsive).
* **Backend:** Laravel 12 API Services.
* **Database:** MySQL.
* **Cloud Hosting & DevOps:** Railway (Dockerized container deployment).

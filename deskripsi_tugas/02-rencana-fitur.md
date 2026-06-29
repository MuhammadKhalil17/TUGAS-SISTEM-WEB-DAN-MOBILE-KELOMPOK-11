# Rencana & Deskripsi Fitur Aplikasi "Kulkasku"

Dokumentasi ini memuat 5 fitur utama yang diimplementasikan di aplikasi Kulkasku Kelompok 11. Setiap fitur mencakup deskripsi fungsional, teknologi, sumber data, dan pembagian peran pengembang.

---

## 1. Fitur Autentikasi Pengguna (Login & Register)

* **Deskripsi Fungsional:**
  Memungkinkan pengguna untuk mendaftarkan akun baru dengan Nama, Email, dan Password. Pengguna terdaftar dapat masuk (Login) untuk mengamankan sesi mereka menggunakan Token API (Sanctum) yang disimpan di browser `localStorage`. Disediakan juga fitur "Akun Demo" instan untuk pengujian cepat.
* **Sumber Data:** Database Internal (`users` table).
* **Peran Penanggung Jawab:**
  * **Backend Developer:** Membuat REST API `/api/v1/auth/register`, `/api/v1/auth/login`, dan `/api/v1/auth/logout`.
  * **Frontend Developer:** Merancang halaman `login.blade.php` & `register.blade.php`, menyimpan token, dan menangani navigasi state.

---

## 2. Fitur Manajemen Kulkas Digital (Isi Kulkas Saya)

* **Deskripsi Fungsional:**
  Pengguna dapat mencatat stok bahan masakan yang ada di rumah mereka secara real-time. Bahan masakan yang dimasukkan akan dikelompokkan secara pintar berdasarkan kategorinya ke dalam rak-rak virtual (Rak Daging/Protein, Rak Sayuran/Buah, dan Rak Dairy/Bahan Lain) dilengkapi dengan visualisasi emoji ikon secara otomatis.
* **Sumber Data:** Database Internal (`ingredients` table linked to `user_id`).
* **Peran Penanggung Jawab:**
  * **Backend Developer:** Menyediakan endpoint API CRUD (`GET`, `POST`, `DELETE`) bahan kulkas dan rute pengosongan kulkas (`/api/v1/fridge/clear`).
  * **Frontend Developer:** Mengembangkan UI kulkas di `fridge.blade.php` dengan grid interaktif, form input instan, dan toast notifikasi.

---

## 3. Fitur Rekomendasi Resep Pintar (Spoonacular API Integration)

* **Deskripsi Fungsional:**
  Sistem akan membaca seluruh bahan makanan yang tersimpan di kulkas pengguna, lalu mengirimkannya ke API pihak ketiga untuk mendapatkan daftar rekomendasi resep masakan yang bisa diolah dengan bahan-bahan tersebut. Hasil pencarian menampilkan nama hidangan, gambar, serta informasi perbandingan bahan yang siap pakai vs bahan yang masih kurang.
* **Sumber Data:** Layanan Pihak Ketiga (Spoonacular API).
* **Peran Penanggung Jawab:**
  * **Backend Developer:** Menghubungkan client request ke Spoonacular API via secure server proxy endpoint `/api/v1/recipes/search` untuk menjaga kerahasiaan API Key.
  * **Frontend Developer:** Merancang halaman `recipes.blade.php`, tombol generator resep, dan visualisasi status kecocokan bahan.

---

## 4. Fitur Detail Cara Memasak (Tab-Based UI)

* **Deskripsi Fungsional:**
  Menyediakan modal detail interaktif saat pengguna mengklik salah satu kartu rekomendasi resep. Detail resep disajikan dalam sistem tab yang memisahkan antara takaran bahan baku (Tab 1: Bahan) dan instruksi langkah memasak (Tab 2: Langkah Memasak) serta dilengkapi informasi estimasi waktu dan porsi.
* **Sumber Data:** Layanan Pihak Ketiga (Spoonacular API).
* **Peran Penanggung Jawab:**
  * **Backend Developer:** Menyediakan endpoint `/api/v1/recipes/{id}/details` untuk mengambil detail instruksi resep dari Spoonacular.
  * **Frontend Developer:** Merancang modal detail responsif dengan tab selector dinamis dan modal overlay di `recipes.blade.php`.

---

## 5. Fitur Buku Resep Favorit (Bookmark System)

* **Deskripsi Fungsional:**
  Pengguna dapat menyimpan resep yang disukai ke dalam daftar favorit pribadi agar dapat diakses kembali dengan cepat tanpa perlu mencarinya ulang. Daftar resep favorit ini disimpan secara permanen di database internal dan dapat dihapus kapan saja.
* **Sumber Data:** Database Internal (`bookmarks` table).
* **Peran Penanggung Jawab:**
  * **Backend Developer:** Menyediakan rute API bookmark (`/api/v1/bookmarks` dan `/api/v1/favorite-recipes`).
  * **Frontend Developer:** Mengintegrasikan tombol simpan favorit di halaman rekomendasi resep dan membuat visualisasi buku resep di halaman `favorites.blade.php`.
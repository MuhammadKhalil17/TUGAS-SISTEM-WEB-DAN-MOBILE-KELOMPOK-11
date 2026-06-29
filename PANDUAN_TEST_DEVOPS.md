# 🧪 Panduan Menjalankan Test — Kulkasku Kelompok 11
### (Untuk DevOps Engineer)

---

## 📋 Prasyarat

Sebelum menjalankan test, pastikan hal-hal berikut sudah siap di laptop:

- **PHP** versi 8.2+ terinstall → cek: `php --version`
- **Composer** terinstall → cek: `composer --version`
- **SQLite** extension aktif di PHP (untuk test database in-memory)
  - Cek dengan: `php -m | findstr sqlite` (Windows) atau `php -m | grep sqlite` (Linux/Mac)
  - Biasanya sudah aktif secara default di Laravel

---

## ⚙️ Setup Sebelum Test (Lakukan Sekali)

```bash
# 1. Clone repo (jika belum)
git clone https://github.com/MuhammadKhalil17/TUGAS-SISTEM-WEB-DAN-MOBILE-KELOMPOK-11.git
cd TUGAS-SISTEM-WEB-DAN-MOBILE-KELOMPOK-11

# 2. Install dependensi PHP
composer install

# 3. Buat file .env untuk testing (copy dari contoh)
cp .env.example .env

# 4. Generate app key
php artisan key:generate
```

> **Catatan:** Test menggunakan database **SQLite in-memory** (`:memory:`) sehingga TIDAK perlu koneksi MySQL saat menjalankan test. Konfigurasinya sudah ada di `phpunit.xml`.

---

## 🚀 Perintah Menjalankan Test

### ▶️ Jalankan Semua Test Sekaligus
```bash
php artisan test
```

### ▶️ Jalankan Hanya Test Fitur (Feature Tests)
```bash
php artisan test --testsuite=Feature
```

### ▶️ Jalankan Hanya Test Unit
```bash
php artisan test --testsuite=Unit
```

### ▶️ Jalankan Test Spesifik Per File
```bash
# Test Autentikasi (Login & Register)
php artisan test tests/Feature/AuthTest.php

# Test Kulkas (CRUD Bahan Makanan)
php artisan test tests/Feature/FridgeTest.php

# Test Resep Favorit (Bookmark)
php artisan test tests/Feature/FavoriteRecipeTest.php
```

### ▶️ Jalankan Dengan Output Detail (Verbose)
```bash
php artisan test --verbose
```

### ▶️ Jalankan Dengan Laporan Coverage (Opsional)
```bash
php artisan test --coverage
```

---

## ✅ Contoh Output Jika Test Berhasil

```
   PASS  Tests\Feature\AuthTest
  ✓ user can register with valid data
  ✓ register fails with duplicate email
  ✓ user can login with correct credentials
  ✓ login fails with wrong password

   PASS  Tests\Feature\FridgeTest
  ✓ authenticated user can view empty fridge
  ✓ authenticated user can add ingredient to fridge
  ✓ authenticated user can delete an ingredient
  ✓ authenticated user can clear fridge
  ✓ unauthenticated user cannot access fridge

   PASS  Tests\Feature\FavoriteRecipeTest
  ✓ authenticated user can view favorite recipes list
  ✓ authenticated user can save a recipe to favorites
  ✓ saving duplicate recipe returns 409 conflict
  ✓ authenticated user can delete a favorite recipe
  ✓ unauthenticated user cannot access favorites

  Tests:    14 passed
  Duration: ~2.50s
```

---

## ❌ Troubleshooting Umum

| Error | Penyebab | Solusi |
|---|---|---|
| `could not find driver (sqlite)` | Extension SQLite PHP belum aktif | Aktifkan `extension=pdo_sqlite` di `php.ini` |
| `Class not found` | Composer belum diinstall | Jalankan `composer install` dulu |
| `APP_KEY not set` | File `.env` belum ada | Jalankan `cp .env.example .env && php artisan key:generate` |
| Test tiba-tiba failed di `FavoriteRecipeTest` | Nama kolom database berbeda | Sampaikan ke Backend Dev untuk cek nama kolom tabel `bookmarks` |

---

## 📁 Lokasi File Test

```
tests/
├── Feature/
│   ├── AuthTest.php            ← Test Login & Register
│   ├── FridgeTest.php          ← Test CRUD Kulkas
│   └── FavoriteRecipeTest.php  ← Test Resep Favorit
└── Unit/
    └── ExampleTest.php         ← Bawaan Laravel (opsional)
```

---

## 🌐 Test Endpoint Production (Manual via curl)

Selain test otomatis, DevOps juga bisa tes endpoint production secara manual:

```bash
# Cek apakah server merespons (harus HTTP 200)
curl -I https://web-production-45d72.up.railway.app/login

# Test endpoint login dengan akun demo
curl -X POST https://web-production-45d72.up.railway.app/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"asisten@kulkasku.com\",\"password\":\"asisten123\"}"

# Simpan token dari response di atas, lalu test endpoint kulkas:
# (ganti YOUR_TOKEN dengan token yang didapat)
curl -X GET https://web-production-45d72.up.railway.app/api/v1/fridge \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

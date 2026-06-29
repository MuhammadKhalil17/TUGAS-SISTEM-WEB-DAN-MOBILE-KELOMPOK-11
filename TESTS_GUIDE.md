# 🧪 Panduan Menjalankan dan Menulis Test Laravel (Untuk DevOps / QA)

Dokumentasi ini berisi perintah-perintah terminal untuk menjalankan tes pada aplikasi Kulkasku, serta contoh cara membuat dan menjalankan unit/feature test.

---

## 🏃‍♂️ 1. Perintah Menjalankan Test (Syntax Utama)

Di Laravel 12, terdapat beberapa cara untuk menjalankan test. DevOps bisa menjalankan perintah ini di terminal root proyek:

### A. Menjalankan Semua Test
```bash
php artisan test
```
*Atau alternatif menggunakan vendor binary secara langsung:*
```bash
./vendor/bin/phpunit
```

### B. Menjalankan Test dengan Detail Output (Verbose)
Menampilkan daftar method test mana saja yang sukses/gagal secara mendetail:
```bash
php artisan test --vvv
```

### C. Menjalankan File Test Tertentu Saja
Jika ingin mengetes file tertentu saja agar prosesnya cepat:
```bash
php artisan test --filter ExampleTest
```

---

## 🛠️ 2. Cara Membuat Test Baru untuk API Endpoint (Opsional tapi Direkomendasikan)

Jika DevOps atau tim QA ingin membuat tes otomatis untuk memverifikasi endpoint API (seperti login, isi kulkas, dll), berikut langkahnya:

### Langkah A: Membuat File Feature Test Baru
Jalankan perintah ini untuk generate file test:
```bash
php artisan make:test ApiAuthenticationTest
```
*Perintah ini akan membuat file baru di `tests/Feature/ApiAuthenticationTest.php`.*

### Langkah B: Contoh Isi Code Test (Contoh Test Login API)
Isi file `tests/Feature/ApiAuthenticationTest.php` dengan kode verifikasi response status login:
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase; // Mereset database otomatis setiap test agar bersih

    public function test_user_can_login_via_api()
    {
        // 1. Buat user dummy di database
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // 2. Kirim POST request ke endpoint login
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // 3. Verifikasi response status dan strukturnya
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data' => [
                         'token',
                         'user' => ['id', 'name', 'email']
                     ]
                 ]);
    }
}
```

### Langkah C: Jalankan Test Baru Tersebut
```bash
php artisan test --filter ApiAuthenticationTest
```

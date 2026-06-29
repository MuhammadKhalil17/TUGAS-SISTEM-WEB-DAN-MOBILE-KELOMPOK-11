# Kulkasku — API Specification (Sisi Backend)

Dokumentasi ini memuat seluruh endpoint REST API yang disediakan oleh server Laravel untuk dikonsumsi oleh aplikasi Frontend (React/Vue).

## Ketentuan Umum Client-Side
1. **Base URL:** `http://localhost:8000` (atau sesuaikan dengan port server Laravel berjalan).
2. **Global Headers:** Untuk semua request privat, wajib menyertakan:
   ```text
   Accept: application/json
   Content-Type: application/json
   Authorization: Bearer <token_akses_kamu>

```

---

##  1. Fitur Autentikasi (Akses Publik)

### A. User Register

* **Method:** `POST`
* **URL:** `/api/v1/auth/register`
* **Deskripsi:** Mendaftarkan akun pengguna baru ke dalam sistem database internal.
* **Request Body (JSON):**

```json
{
  "name": "Nama Pengguna",
  "email": "user@example.com",
  "password": "password123"
}

```

* **Response Sukses (`201 Created`):**

```json
{
  "status": "success",
  "message": "User registered successfully"
}

```

* **Response Gagal (`422 Unprocessable Entity`):**

```json
{
  "status": "error",
  "message": "The email has already been taken."
}

```

### B. User Login

* **Method:** `POST`
* **URL:** `/api/v1/auth/login`
* **Deskripsi:** Memverifikasi email & password, lalu mengembalikan token akses untuk sesi privat.
* **Request Body (JSON):**

```json
{
  "email": "user@example.com",
  "password": "password123"
}

```

* **Response Sukses (`200 OK`):**
* *Catatan Frontend: Simpan teks `token` di localStorage/state untuk dipakai di request berikutnya.*

```json
{
  "status": "success",
  "data": {
    "token": "1|kB9x...Rk7a8p9qWvXz",
    "user": {
      "id": 1,
      "name": "Nama Pengguna",
      "email": "user@example.com"
    }
  }
}

```

* **Response Gagal (`401 Unauthorized`):**

```json
{
  "status": "error",
  "message": "Invalid email or password"
}

```

---

## 2. Fitur Manajemen Kulkas (Butuh Token Login)

### A. Lihat Isi Kulkas (Get Items)

* **Method:** `GET`
* **URL:** `/api/v1/fridge`
* **Deskripsi:** Mengambil semua daftar bahan makanan digital yang dimiliki oleh user terkait.
* **Request Body:** `-`
* **Response Sukses (`200 OK`):**

```json
{
  "status": "success",
  "data": {
    "ingredients": [
      {
        "id": 1,
        "name": "egg"
      },
      {
        "id": 2,
        "name": "tomato"
      }
    ]
  }
}

```

### B. Tambah Bahan Ke Kulkas (Add Item)

* **Method:** `POST`
* **URL:** `/api/v1/refrigerator`
* **Deskripsi:** Memasukkan bahan makanan baru ke dalam inventaris kulkas digital user.
* **Request Body (JSON):**

```json
{
  "name": "onion"
}

```

* **Response Sukses (`200 OK`):**

```json
{
  "status": "success",
  "message": "Ingredient added to fridge successfully",
  "data": {
    "id": 3,
    "name": "onion"
  }
}

```

---

## 3. Fitur Generator Resep (Butuh Token Login)

### A. Cari Resep Berdasarkan Bahan

* **Method:** `POST`
* **URL:** `/api/v1/recipes/search`
* **Deskripsi:** Mengirimkan array nama bahan makanan, lalu server akan mengembalikan rekomendasi resep masakan dari Spoonacular API.
* **Request Body (JSON):**

```json
{
  "ingredients": ["egg", "tomato", "onion"]
}

```

* **Response Sukses (`200 OK`):**

```json
{
  "status": "success",
  "data": [
    {
      "id": 648438,
      "title": "Tomato and Egg Scramble",
      "image": "[https://spoonacular.com/recipeImages/648438-312x231.jpg](https://spoonacular.com/recipeImages/648438-312x231.jpg)",
      "usedIngredientCount": 2,
      "missedIngredientCount": 1
    }
  ]
}

```

### B. Lihat Detail Instruksi Resep

* **Method:** `GET`
* **URL:** `/api/v1/recipes/{id}/details`
* **Deskripsi:** Mengambil detail takaran, estimasi waktu, dan instruksi memasak langkah-demi-langkah berdasarkan ID resep dari Spoonacular API.
* **Request Body:** `-`
* **Response Sukses (`200 OK`):**

```json
{
  "status": "success",
  "data": {
    "id": 648438,
    "title": "Tomato and Egg Scramble",
    "readyInMinutes": 15,
    "servings": 2,
    "calories": 245,
    "instructions": "1. Beat the eggs in a bowl. 2. Chop tomatoes and onions. 3. Heat oil and stir-fry.",
    "extendedIngredients": [
      "2 large eggs",
      "2 medium tomatoes",
      "1/2 onion"
    ]
  }
}

```

---

## 4. Fitur Resep Favorit (Butuh Token Login)

### A. Simpan Resep Ke Favorit

* **Method:** `POST`
* **URL:** `/api/v1/favorite-recipes`
* **Deskripsi:** Menyimpan data ringkas resep pilihan ke database internal server agar bisa dibuka kapan saja.
* **Request Body (JSON):**

```json
{
  "recipe_id": 648438,
  "title": "Tomato and Egg Scramble",
  "image": "[https://spoonacular.com/recipeImages/648438-312x231.jpg](https://spoonacular.com/recipeImages/648438-312x231.jpg)"
}

```

* **Response Sukses (`200 OK`):**

```json
{
  "status": "success",
  "message": "Recipe successfully bookmarked"
}

```

* **Response Gagal (`409 Conflict` - Jika sudah pernah disimpan):**

```json
{
  "status": "error",
  "message": "Recipe is already bookmarked"
}
```
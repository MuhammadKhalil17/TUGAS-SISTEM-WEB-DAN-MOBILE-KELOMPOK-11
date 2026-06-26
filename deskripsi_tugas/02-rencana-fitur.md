# Kulkasku — API Specification (Sisi Backend)

Dokumentasi ini memuat seluruh endpoint REST API yang disediakan oleh server Laravel untuk dikonsumsi oleh aplikasi Frontend (React/Vue).

## Ketentuan Umum Client-Side
1. **Base URL:** `http://localhost:8000` (atau sesuaikan dengan port server Laravel berjalan).
2. **Global Headers:** Untuk semua request privat, wajib menyertakan:
   ```text
   Accept: application/json
   Content-Type: application/json
   Authorization: Bearer <token_akses_kamu>
1. Fitur Autentikasi (Akses Publik)
A. User Register
Method: POST

URL: /api/v1/auth/register

Deskripsi: Mendaftarkan akun pengguna baru ke dalam sistem database internal.

Request Body (JSON):

JSON
{
  "name": "Nama Pengguna",
  "email": "user@example.com",
  "password": "password123"
}
Response Sukses (201 Created):

JSON
{
  "status": "success",
  "message": "User registered successfully"
}
Response Gagal (422 Unprocessable Entity):

JSON
{
  "status": "error",
  "message": "The email has already been taken."
}
B. User Login
Method: POST

URL: /api/v1/auth/login

Deskripsi: Memverifikasi email & password, lalu mengembalikan token akses untuk sesi privat.

Request Body (JSON):

JSON
{
  "email": "user@example.com",
  "password": "password123"
}
Response Sukses (200 OK):

JSON
{
  "status": "success",
  "token": "1|2v7bX0XbM...",
  "user": {
    "id": 12,
    "name": "Nama Pengguna",
    "email": "user@example.com"
  }
}
2. Fitur Kulkas Saya (Butuh Token Login)
A. Lihat Isi Kulkas
Method: GET

URL: /api/v1/refrigerator

Deskripsi: Mengambil daftar semua bahan makanan yang telah dimasukkan oleh user terkait.

Response Sukses (200 OK):

JSON
{
  "status": "success",
  "ingredients": [
    { "id": 1, "name": "egg" },
    { "id": 2, "name": "chicken" },
    { "id": 3, "name": "onion" }
  ]
}
B. Tambah Bahan Makanan ke Kulkas
Method: POST

URL: /api/v1/refrigerator

Request Body (JSON):

JSON
{
  "ingredients": ["garlic", "tomato"]
}
Response Sukses (201 Created):

JSON
{
  "status": "success",
  "message": "Ingredients added successfully"
}
C. Hapus Semua Bahan Makanan (Clear Kulkas)
Method: DELETE

URL: /api/v1/refrigerator/clear

Deskripsi: Mengosongkan seluruh isi kulkas digital milik user sekaligus.

Response Sukses (200 OK):

JSON
{
  "status": "success",
  "message": "All ingredients cleared from refrigerator"
}
D. Hapus Satu Bahan Makanan
Method: DELETE

URL: /api/v1/refrigerator/{id}

Deskripsi: Menghapus satu item bahan makanan tertentu berdasarkan ID item.

Response Sukses (200 OK):

JSON
{
  "status": "success",
  "message": "Ingredient deleted"
}
3. Fitur Pencarian & Detail Resep (Spoonacular Proxy via Token Login)
A. Cari Resep Berdasarkan Bahan Baku
Method: POST

URL: /api/v1/recipes/search

Deskripsi: Menerima input array bahan baku, lalu mengembalikan kecocokan resep dari Spoonacular API.

Request Body (JSON):

JSON
{
  "ingredients": ["egg", "tomato"]
}
Response Sukses (200 OK):

JSON
[
  {
    "id": 648438,
    "title": "Tomato and Egg Scramble",
    "image": "[https://spoonacular.com/recipeImages/648438-312x231.jpg](https://spoonacular.com/recipeImages/648438-312x231.jpg)",
    "usedIngredientCount": 2,
    "missedIngredientCount": 1
  }
]
B. Lihat Detail Instruksi Resep
Method: GET

URL: /api/v1/recipes/{id}/details

Deskripsi: Mengambil detail langkah memasak lengkap berdasarkan ID unik resep.

Response Sukses (200 OK):

JSON
{
  "status": "success",
  "data": {
    "id": 648438,
    "title": "Tomato and Egg Scramble",
    "readyInMinutes": 15,
    "servings": 2,
    "instructions": "1. Beat the eggs in a bowl. 2. Chop tomatoes and onions. 3. Heat oil and stir-fry.",
    "extendedIngredients": [
      "2 large eggs",
      "2 medium tomatoes"
    ]
  }
}
4. Fitur Buku Resep Favorit (Butuh Token Login)
A. Tampilkan Semua Resep Favorit
Method: GET

URL: /api/v1/favorite-recipes

Deskripsi: Mengambil semua resep yang pernah disimpan oleh user yang sedang login.

Response Sukses (200 OK):

JSON
[
  {
    "id": 1,
    "recipe_id": 648438,
    "title": "Tomato and Egg Scramble",
    "image": "[https://spoonacular.com/recipeImages/648438-312x231.jpg](https://spoonacular.com/recipeImages/648438-312x231.jpg)"
  }
]
B. Simpan Resep Ke Daftar Favorit
Method: POST

URL: /api/v1/favorite-recipes

Deskripsi: Menyimpan data ringkas resep pilihan ke database internal server.

Request Body (JSON):

JSON
{
  "recipe_id": 648438,
  "title": "Tomato and Egg Scramble",
  "image": "[https://spoonacular.com/recipeImages/648438-312x231.jpg](https://spoonacular.com/recipeImages/648438-312x231.jpg)"
}
Response Sukses (201 Created):

JSON
{
  "id": 1,
  "user_id": 12,
  "recipe_id": 648438,
  "title": "Tomato and Egg Scramble",
  "image": "[https://spoonacular.com/recipeImages/648438-312x231.jpg](https://spoonacular.com/recipeImages/648438-312x231.jpg)"
}
Response Gagal Duplikat (409 Conflict):

JSON
{
  "status": "error",
  "message": "Resep ini sudah ada di dalam daftar favorit kamu."
}
C. Hapus Resep Dari Daftar Favorit
Method: DELETE

URL: /api/v1/favorite-recipes/{recipeId}

Deskripsi: Menghapus resep dari daftar pustaka favorit milik pengguna berdasarkan ID resep.

Response Sukses (200 OK):

JSON
{
  "status": "success",
  "message": "Resep berhasil dihapus dari daftar favorit."
}
Response Gagal Tidak Ditemukan (404 Not Found):

JSON
{
  "status": "error",
  "message": "Resep favorit tidak ditemukan atau sudah dihapus."
}
# Panduan Deployment Aplikasi Kulkasku 🚀

> [!NOTE]
> **Aplikasi Live Production**: Aplikasi Kulkasku Kelompok 11 saat ini aktif dan dapat diakses publik di:
> **URL**: [https://web-production-45d72.up.railway.app](https://web-production-45d72.up.railway.app)
> *Akun Uji Coba: `asisten@kulkasku.com` / `asisten123`*

---

## 📋 Prasyarat Sebelum Deploy (Production Checks)

Sebelum di-upload ke server production, pastikan Anda telah melakukan konfigurasi berikut:

1. **Atur Environment ke Production** di file `.env`:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://nama-domain-anda.com
   ```
2. **Kompilasi Aset Frontend (CSS & JS)** secara lokal sebelum di-upload (terutama jika server tujuan tidak memiliki Node.js):
   ```bash
   npm run build
   ```
   *Langkah ini akan menghasilkan folder `public/build/` yang berisi aset minified.*
3. **Optimalkan Laravel Cache** untuk performa maksimal:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## 📂 Metode 1: Shared Hosting (cPanel / DirectAdmin)

Ini adalah metode paling umum dan ekonomis untuk proyek mahasiswa.

### Langkah 1: Siapkan Arsip ZIP
1. Compress seluruh isi folder proyek `TUGAS-SISTEM-WEB-DAN-MOBILE-KELOMPOK-11` menjadi berkas `.zip`.
2. *Catatan: Pastikan folder `vendor/` dan `node_modules/` **tidak ikut dikompres** untuk menghemat ukuran berkas.*

### Langkah 2: Upload dan Ekstrak di cPanel
1. Masuk ke cPanel hosting Anda, lalu buka **File Manager**.
2. Masuk ke root directory (di luar folder `public_html` sangat disarankan untuk keamanan). Buat folder baru bernama `kulkasku-core` dan upload berkas `.zip` ke folder tersebut, lalu ekstrak.
3. Pindahkan isi dari folder `kulkasku-core/public/` ke dalam folder `public_html/`.

### Langkah 3: Sesuaikan Path index.php
Karena folder core berada di luar `public_html`, sesuaikan referensi path di file `public_html/index.php`:
```php
// Cari baris ini (sekitar baris 47):
require __DIR__.'/../bootstrap/app.php'

// Ubah menjadi path folder core Anda:
require __DIR__.'/../kulkasku-core/bootstrap/app.php'
```

### Langkah 4: Konfigurasi Database & Environment
1. Buat database baru dan user database melalui menu **MySQL Database Wizard** di cPanel.
2. Edit file `.env` di dalam folder `kulkasku-core/` untuk menghubungkan ke database baru tersebut.
3. Jalankan migrasi database. Jika Anda memiliki akses SSH ke hosting:
   ```bash
   php artisan migrate --seed
   ```
   *Jika tidak memiliki SSH, Anda bisa mengekspor database lokal Anda (.sql) dan mengimpornya via **phpMyAdmin** di cPanel.*

---

## 🖥️ Metode 2: Virtual Private Server (VPS - Ubuntu + Nginx)

Metode ini memberikan kontrol penuh atas server dan performa terbaik.

### Langkah 1: Install LEMP Stack
Hubungkan ke VPS via SSH dan instal dependensi yang diperlukan:
```bash
sudo apt update
sudo apt install nginx mysql-server php-fpm php-mysql php-cli php-mbstring php-xml php-bcmath php-curl php-zip unzip git -y
```

### Langkah 2: Install Composer & Node.js
```bash
# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js (via NVM)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash
source ~/.bashrc
nvm install 20
```

### Langkah 3: Kloning Repositori & Setup Proyek
1. Masuk ke folder `/var/www/` dan kloning repositori Anda:
   ```bash
   cd /var/www
   git clone <URL_REPOS_ANDA> kulkasku
   cd kulkasku
   ```
2. Salin file `.env` dan pasang dependensi:
   ```bash
   cp .env.example .env
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   ```
3. Atur hak akses folder storage dan bootstrap:
   ```bash
   sudo chown -R www-data:www-data /var/www/kulkasku/storage
   sudo chown -R www-data:www-data /var/www/kulkasku/bootstrap/cache
   sudo chmod -R 775 /var/www/kulkasku/storage
   ```
4. Generate App Key dan jalankan migrasi database:
   ```bash
   php artisan key:generate
   php artisan migrate --force --seed
   ```

### Langkah 4: Konfigurasi Nginx
Buat file konfigurasi server block baru di `/etc/nginx/sites-available/kulkasku`:
```nginx
server {
    listen 80;
    server_name nama-domain-anda.com;
    root /var/www/kulkasku/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock; # Sesuaikan dengan versi PHP Anda
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
Aktifkan konfigurasi dan restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/kulkasku /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## ☁️ Metode 3: PaaS (Railway / Render / Heroku)

Layanan cloud modern dengan integrasi Git otomatis.

### 1. Buat Dokumen Dockerfile (Sangat Disarankan)
Untuk memastikan aplikasi berjalan dengan baik di cloud, buat berkas `Dockerfile` di root proyek Anda untuk membundel PHP + Nginx + Node.js.

### 2. Atur Environment Variables di Dashboard PaaS
Di dashboard Railway/Render Anda, tambahkan variabel lingkungan berikut:
* `APP_KEY` = *(Hasilkan secara lokal dengan `php artisan key:generate`)*
* `APP_ENV` = `production`
* `APP_DEBUG` = `false`
* `DB_CONNECTION` = `mysql`
* *(Dan data koneksi database MySQL cloud yang disediakan oleh platform tersebut)*

### 3. Buat Build Command / Start Command
* **Build Command**: `composer install --no-dev && npm install && npm run build`
* **Start Command**: `php artisan migrate --force --seed && apache2-foreground` (atau start-script web server yang sesuai).

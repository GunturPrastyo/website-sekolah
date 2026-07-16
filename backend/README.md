# Website Sekolah - Backend

Backend API untuk aplikasi website sekolah. Dibangun menggunakan framework Laravel. Menyediakan endpoint untuk otentikasi, manajemen konten, data akademik, dan lain-lain.

## Prasyarat

- [PHP](https://www.php.net/) (Direkomendasikan versi 8.3 atau lebih tinggi)
- [Composer](https://getcomposer.org/)
- Database Server (misal: MySQL, MariaDB)
- Web Server (misal: Nginx, Apache, atau server bawaan Laravel untuk development)

## Instalasi

1.  **Masuk ke direktori backend:**
    ```bash
    cd backend
    ```

2.  **Install dependensi PHP:**
    ```bash
    composer install
    ```

3.  **Konfigurasi Environment:**
    Salin file `.env.example` menjadi file baru bernama `.env`.
    ```bash
    cp .env.example .env
    ```

4.  **Generate Kunci Aplikasi:**
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi File `.env`:**
    Buka file `.env` dan sesuaikan variabel berikut, terutama untuk koneksi database dan URL aplikasi.

    ```env
    # URL Aplikasi
    APP_URL=http://localhost:8000
    FRONTEND_URL=http://localhost:5173

    # Konfigurasi Database
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=user_database_anda
    DB_PASSWORD=password_database_anda

    # Konfigurasi Redis (Sangat direkomendasikan untuk production)
    CACHE_DRIVER=redis
    SESSION_DRIVER=redis
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379

    # Konfigurasi Google Login (Opsional)
    # Dapatkan dari Google Cloud Console
    GOOGLE_CLIENT_ID=
    GOOGLE_CLIENT_SECRET=
    GOOGLE_REDIRECT_URI=${APP_URL}/api/auth/google/callback
    ```

6.  **Migrasi Database:**
    Jalankan migrasi untuk membuat semua tabel yang dibutuhkan di database. Tambahkan `--seed` untuk Anda memiliki *seeder* data awal.
    ```bash
    php artisan migrate --seed
    ```

7.  **Buat Symbolic Link:**
    Perintah ini penting agar file yang diunggah (seperti gambar) dapat diakses dari web.
    ```bash
    php artisan storage:link
    ```

8.  **Menjalankan Server Development:**
    Jalankan server development bawaan Laravel.
    ```bash
    php artisan serve
    ```
    API akan tersedia di `http://localhost:8000` (atau port lain yang ditampilkan).

## Teknologi Utama

- **Framework**: Laravel
- **Otentikasi API**: Laravel Sanctum (Token Based)
- **Otentikasi Sosial**: Laravel Socialite (untuk Google Login)
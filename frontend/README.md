# Website Sekolah - Frontend

Bagian frontend dari aplikasi website sekolah. Dibangun menggunakan Vue.js 3 dan Vite, dengan fokus pada panel administrasi untuk mengelola konten website.

## Prasyarat

- [Node.js](https://nodejs.org/) (Direkomendasikan versi LTS terbaru, misal: 18.x atau 20.x)
- [npm](https://www.npmjs.com/) (biasanya sudah terpasang bersama Node.js)

## Instalasi

1.  **Masuk ke direktori frontend:**
    ```bash
    cd frontend
    ```

2.  **Install dependensi:**
    Jalankan perintah berikut untuk mengunduh dan menginstall semua paket yang dibutuhkan.
    ```bash
    npm install
    ```

3.  **Konfigurasi Environment:**
    Salin file `.env.example` menjadi file baru bernama `.env`. Perintah ini akan membuat file konfigurasi lokal Anda.
    ```bash
    cp .env.example .env
    ```
    Pastikan variabel di dalam file `.env` yang baru sudah benar. Secara default, `VITE_API_BASE_URL` akan diatur ke `http://localhost:8000`, yang sesuai untuk development lokal dengan backend Laravel.

4.  **Menjalankan Server Development:**
    Untuk menjalankan aplikasi di mode development dengan *hot-reloading*.
    ```bash
    npm run dev
    ```
    Aplikasi akan tersedia di `http://localhost:5173` (atau port lain yang tersedia).

5.  **Build untuk Produksi:**
    Untuk membuat versi produksi yang teroptimasi dari aplikasi.
    ```bash
    npm run build
    ```
    Hasil build akan tersedia di direktori `dist`.

## Teknologi Utama

- **Framework**: Vue.js 3 (dengan Composition API)
- **Build Tool**: Vite
- **Routing**: Vue Router
- **Styling**: Tailwind CSS
- **HTTP Client**: Axios
- **Icons**: Phosphor Icons
- **Editor Teks**: VueQuill
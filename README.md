Instalasi (Deploy Secara Lokal)
Ikuti langkah berikut untuk menjalankan aplikasi ini secara lokal:

### 1. Clone Repository
```bash
git clone https://github.com/MuhamadRifai1311/test_skill.git
cd test_skill

### 1. install depedency laravel
- composer install
### lalu jalankan
- cp .env.example .env
- php artisan key:generate
- lalu Create database test_skill di Mysql

### sesuaikan konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test_skill
DB_USERNAME=root
DB_PASSWORD=


### jalankan migrasi
- php artisan migrate

### jalankan server
- php artisan serve
- lalu akses  http://127.0.0.1:8000

### jalankan vite di laravel
- npm run dev

### jika cetak tidak berjalan maka jalankan dulu ini
- composer require barryvdh/laravel-dompdf

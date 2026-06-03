<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Installation
 1. download the resources into your machine
 2. install `composer` & `node` dependencies

    `composer install`  
    `npm install`

 3. copy `.env.example` file to `.env` on the root folder.

    `cp .env.example .env` or `copy .env.example .env`

 4. set your local database in `.env`  file

    `DB_CONNECTION=mysql`  
	`DB_HOST=127.0.0.1`  
	`DB_PORT=3306`  
	`DB_DATABASE= *DATABASE NAME*`  
	`DB_USERNAME= *USERNAME*`  
	`DB_PASSWORD= *PASSWORD*`

6. generate key & migrate databse

	`php artisan key:generate`  
	`php artisan migrate`

7. run the project locally

    `php artisan serve`

8. and lastly in another terminal tab run react locally

   `npm run dev`

## PDF / Browsershot Setup on Linux VPS

Untuk memastikan fitur cetak PDF menggunakan `spatie/browsershot` berfungsi dengan benar di server Linux VPS (Ubuntu/Debian):

### Opsi A: Untuk VPS berbasis Intel/AMD (x86_64)

Jalankan perintah berikut secara berurutan di terminal SSH server (menggunakan user `root` atau `sudo`):

1. **Instal Google Chrome Resmi (Versi Non-Snap)**:
   ```bash
   # Hapus Chromium Snap bawaan Ubuntu (jika ada) untuk menghindari permission issues
   sudo apt purge -y chromium-browser
   sudo snap remove chromium

   # Unduh paket installer Google Chrome resmi (.deb)
   wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb

   # Instal Google Chrome
   sudo dpkg -i google-chrome-stable_current_amd64.deb
   sudo apt-get install -f
   ```

2. **Dapatkan Path Google Chrome**:
   Jalankan `which google-chrome` (biasanya mengembalikan `/usr/bin/google-chrome`).

3. **Konfigurasikan `.env` Server**:
   ```env
   CHROME_PATH=/usr/bin/google-chrome
   BROWSERSHOT_NO_SANDBOX=true
   ```

---

### Opsi B: Untuk VPS berbasis ARM64 (seperti Oracle Cloud Free Tier / Ampere)

Jalankan perintah berikut secara berurutan di terminal SSH server (menggunakan user `root` atau `sudo`):

1. **Instal Chromium Native (.deb via PPA)**:
   ```bash
   # Hapus Chromium Snap bawaan Ubuntu (jika ada) untuk menghindari permission issues
   sudo snap remove chromium
   sudo apt purge -y chromium-browser

   # Tambahkan PPA xtradeb yang menyediakan Chromium versi native (.deb) untuk ARM64
   sudo add-apt-repository ppa:xtradeb/apps -y
   sudo apt update

   # Instal Chromium
   sudo apt install -y chromium
   ```

2. **Dapatkan Path Chromium**:
   Jalankan `which chromium` (biasanya mengembalikan `/usr/bin/chromium`).

3. **Konfigurasikan `.env` Server**:
   ```env
   CHROME_PATH=/usr/bin/chromium
   BROWSERSHOT_NO_SANDBOX=true
   ```

---

### Langkah Final (Semua Arsitektur)

Setelah melakukan perubahan konfigurasi `.env`, jalankan perintah pembersihan cache di root folder proyek:
```bash
php artisan config:cache
```

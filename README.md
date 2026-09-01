# Servisa

Aplikasi layanan servis kendaraan berbasis Laravel 12. Lingkungan Docker terdiri dari Nginx, PHP 8.2-FPM, dan MySQL 8.4.

## Menjalankan dengan Docker

Prasyarat: Docker Desktop sudah berjalan.

1. Buat konfigurasi lokal:

   ```powershell
   Copy-Item .env.example .env
   ```

2. Build dan jalankan seluruh container:

   ```powershell
   docker compose up -d --build
   ```

   Saat pertama dijalankan, container aplikasi otomatis menjalankan `composer install`, membuat `APP_KEY` bila masih kosong, menjalankan migrasi, dan mengisi data awal.

3. Buka aplikasi di [http://localhost:8000](http://localhost:8000).

Perintah yang berguna:

```powershell
docker compose ps
docker compose logs -f
docker compose exec app php artisan test
docker compose down
```

MySQL dapat diakses dari komputer host melalui port `3307`. Data disimpan secara permanen pada volume `servisa_mysql_data`. Untuk menghapus container tanpa menghapus database, gunakan `docker compose down` tanpa opsi `-v`.

Akun demo:

| Username | Password | Level |
|---|---|---|
| `pelanggan` | `pelanggan` | Pelanggan |
| `mekanik` | `mekanik` | Mekanik |
| `admin` | `admin` | Admin |
| `pemilik` | `pemilik` | Pemilik |

## Loading Global Servisa

Semua form `POST`, `PUT`, `PATCH`, dan `DELETE` otomatis menampilkan overlay serta mencegah double-submit. Teks proses dapat ditentukan pada form:

```blade
<form method="POST" data-servisa-loader-text="Menyimpan data...">
```

Loader dapat dipanggil langsung atau dipakai sebagai komponen inline:

```javascript
showServisaLoader('Mencari mekanik...');
hideServisaLoader();
```

```blade
<x-servisa-loader text="Memuat laporan..." />
```

Pemanggilan `fetch` otomatis memakai loader dan menyembunyikannya di `finally`. Gunakan opsi berikut untuk mengatur teks atau melewati loader pada request yang benar-benar instan:

```javascript
fetch('/api/servis', { servisaLoaderText: 'Mengirim permintaan...' });
fetch('/api/status', { servisaLoader: false });
```

## Struktur Container

- `web`: Nginx, port `8000`.
- `app`: PHP 8.2-FPM dan Laravel.
- `db`: MySQL 8.4, port host `3307`.

---

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

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

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

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

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

## 🔗 Akses Aplikasi

Aplikasi sisi klien (*Frontend*) yang terintegrasi dan mengonsumsi layanan API dari repositori ini dapat diakses secara langsung melalui tautan *live deployment* berikut:
**[Akses Portal Shield Basketball Camp](https://ahp-frontend.thankfulfield-aee3897d.eastasia.azurecontainerapps.io/)**

## 🏗️ Struktur Direktori Utama

Proyek ini dibangun menggunakan kerangka kerja Laravel. Berikut adalah penjelasan struktur direktori utama yang digunakan untuk memisahkan logika bisnis dan pengolahan data:

```text
shield-basketball-servicesv2/
├── app/
│   ├── Http/
│   │   ├── Controllers/   # Menangani HTTP request dari Frontend dan mengembalikan response JSON
│   │   ├── Requests/      # Validasi form request untuk input data evaluasi dan kriteria
│   │   └── Middleware/    # Filter HTTP request, termasuk autentikasi JWT/Sanctum untuk pelatih
│   ├── Models/            # Representasi entitas tabel database (Pemain, Kriteria, NilaiTes, dll)
│   └── Services/          # [PENTING] Memuat logika bisnis inti, termasuk kalkulasi algoritma Pure AHP
├── bootstrap/             # Skrip inisialisasi aplikasi Laravel
├── config/                # Berkas konfigurasi aplikasi (Database, CORS, dll)
├── database/
│   ├── migrations/        # Skema tabel untuk database PostgreSQL (Supabase)
│   └── seeders/           # Data dummy/awal untuk akun pelatih dan data kriteria dasar
├── routes/
│   ├── api.php            # [PENTING] Definisi rute API yang akan dipanggil oleh aplikasi Frontend Next.js
│   └── web.php            # Rute web default (tidak banyak digunakan karena aplikasi berbasis API)
├── storage/               # Penyimpanan log sistem dan file cache
└── tests/                 # Unit test dan feature test untuk menguji keakuratan perhitungan AHP

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

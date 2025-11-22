
# SantuyTL

> SantuyTL adalah platform komunitas, edukasi, dan hiburan berbasis Laravel yang menyediakan berbagai fitur seperti forum, event, kursus, sistem gamifikasi, dan integrasi Discord.

## Fitur Utama

- Forum diskusi dan thread
- Sistem event dan partisipasi
- Kursus dan chapter pembelajaran
- Sistem badge/lencana
- Gamifikasi: XP, level, prestige, coin, leaderboard
- Integrasi Discord (login, role, avatar)
- Sistem inventori, market, dan trade
- Sistem tim, kompetisi, dan skor
- Sistem tiket dan pelaporan
- Dashboard statistik user

## Struktur Folder

- `app/Models/` — Model database (User, UserStat, Badge, Event, Job, dsb)
- `app/Http/Controllers/` — Controller utama (Forum, Event, Course, Market, dsb)
- `routes/` — Routing aplikasi (`web.php`, `api.php`)
- `resources/views/` — Blade template untuk tampilan web
- `database/migrations/` — Migrasi struktur database
- `public/` — Entry point aplikasi (index.php)

## Instalasi

1. Clone repository:
	```bash
	git clone https://github.com/Fikri-Alfarizi/santuytl.git
	cd santuytl
	```
2. Install dependency PHP & JS:
	```bash
	composer install
	npm install && npm run build
	```
3. Copy file `.env.example` ke `.env` dan atur konfigurasi database:
	```bash
	cp .env.example .env
	php artisan key:generate
	```
4. Jalankan migrasi dan seeder:
	```bash
	php artisan migrate --seed
	```
5. Jalankan server lokal:
	```bash
	php artisan serve
	```

## Penggunaan

- Login/register menggunakan Discord
- Akses fitur forum, kursus, event, market, dsb
- Admin panel untuk manajemen user, event, dan konten

## Kontribusi

Pull request dan issue sangat diterima! Silakan fork repo ini dan buat perubahan sesuai kebutuhan komunitas.

## Lisensi

MIT

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

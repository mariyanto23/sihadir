# HadirKu

HadirKu adalah sistem presensi SD berbasis pengenalan wajah dengan portal Admin, Siswa, dan Orang Tua. Aplikasi dibuat dengan Laravel, Blade, Livewire, Tailwind CSS, MySQL, Spatie Laravel Permission, Laravel Excel, DomPDF, Laravel Scheduler, Laravel Storage, dan face-api.js via CDN.

## Fitur Utama

- Login role `admin`, `student`, dan `parent`.
- Dashboard admin dengan statistik presensi harian.
- CRUD siswa, kelas, akun, hari libur, dan pengaturan.
- Upload foto siswa ke storage private dengan route akses aman.
- Registrasi descriptor wajah siswa memakai kamera browser dan face-api.js.
- Presensi wajah siswa dengan threshold configurable, cooldown 5 detik, dan upsert presensi harian.
- Pengajuan izin/sakit siswa dan approval admin yang otomatis membuat attendance record.
- Portal orang tua untuk melihat anak dan riwayat presensi.
- Laporan presensi dengan export Excel dan PDF.
- Command `attendance:mark-absent` dan scheduler harian pukul 07:00.

## Requirement

- PHP 8.3+
- Composer
- Node.js dan npm
- MySQL 8+
- HTTPS untuk kamera browser di production, atau `localhost` saat development

## Contoh `.env`

```env
APP_NAME=HadirKu
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hadirku
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=local
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

## Instalasi

1. Buat database MySQL:

```bash
mysql -u root -e "CREATE DATABASE hadirku CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

2. Install dependency:

```bash
composer install
npm install
```

3. Siapkan environment:

```bash
copy .env.example .env
php artisan key:generate
```

4. Migrasi dan seed data awal:

```bash
php artisan migrate --seed
```

5. Build asset:

```bash
npm run build
```

6. Jalankan aplikasi:

```bash
php artisan serve
```

Akses aplikasi di `http://localhost:8000`.

## Akun Default

- Email: `admin@hadirku.test`
- Password: `password`

## Scheduler

Untuk menjalankan scheduler Laravel:

```bash
php artisan schedule:work
```

Command manual auto Tidak Hadir:

```bash
php artisan attendance:mark-absent
php artisan attendance:mark-absent --date=2026-05-12
```

Scheduler akan menjalankan command setiap hari pukul 07:00 dan melewati hari libur atau hari di luar mode sekolah.

## Presensi Wajah

1. Admin login.
2. Buka `Siswa`, buat data siswa, lalu buka detail siswa.
3. Gunakan panel `Registrasi Wajah` untuk menyimpan descriptor.
4. Buat akun siswa di menu `Akun` dan hubungkan ke data siswa.
5. Siswa login, buka `Scan`, lalu lakukan presensi wajah.

Descriptor wajah disimpan sebagai JSON di MySQL. Foto siswa disimpan di disk `local` pada storage private dan hanya dapat diakses melalui route yang melewati policy.

## Testing

```bash
php artisan test
```

Test yang tersedia mencakup akses role, policy orang tua terhadap anak, status Hadir/Terlambat, dan auto Tidak Hadir tanpa duplikasi.

# Arsitektur Sistem HadirKu Laravel

Dokumen ini menjelaskan arsitektur teknis aplikasi **HadirKu**, yaitu sistem presensi SD berbasis **Laravel + MySQL + Blade/Livewire**.

## 1. Gambaran Umum

Aplikasi HadirKu adalah aplikasi web presensi siswa untuk sekolah dasar. Sistem memiliki tiga portal utama:

- Admin
- Siswa
- Orang Tua

Teknologi utama:

- Laravel sebagai backend utama.
- MySQL sebagai database relasional.
- Blade + Livewire untuk antarmuka dinamis.
- Tailwind CSS untuk styling.
- face-api.js untuk pengenalan wajah di sisi browser.
- Laravel Storage untuk foto siswa.
- Laravel Scheduler untuk proses otomatis, seperti menandai siswa tidak hadir.

```text
┌──────────────────────────────┐
│           Pengguna            │
│   Admin / Siswa / Orang Tua   │
└───────────────┬──────────────┘
                │ HTTPS
                ▼
┌──────────────────────────────────────────┐
│        Laravel Web Application            │
│  Blade + Livewire + Tailwind CSS          │
│  Auth, Role, Policy, Middleware           │
└───────────────┬──────────────────────────┘
                │
                ▼
┌──────────────────────────────────────────┐
│              Service Layer                │
│ AttendanceService                         │
│ FaceRecognitionService                    │
│ ReportService                             │
│ SettingService                            │
└───────────────┬──────────────────────────┘
                │
                ▼
┌──────────────────────────────────────────┐
│                  MySQL                    │
│ users, students, attendance_records, etc. │
└───────────────┬──────────────────────────┘
                │
                ▼
┌──────────────────────────────────────────┐
│           Laravel Storage                 │
│ Foto siswa, logo sekolah, dokumen         │
└──────────────────────────────────────────┘
```

## 2. Prinsip Arsitektur

1. Laravel menangani seluruh autentikasi, otorisasi, validasi, dan penyimpanan data.
2. Livewire digunakan untuk halaman interaktif seperti dashboard, CRUD siswa, izin, dan laporan.
3. JavaScript digunakan khusus untuk fitur browser-native seperti kamera dan face-api.js.
4. Pengenalan wajah dilakukan di browser, bukan server.
5. Server hanya menyimpan descriptor wajah dalam format JSON.
6. Semua akses data harus melewati middleware, policy, atau service.
7. Jangan menaruh query kompleks langsung di Blade.
8. Gunakan service class untuk logika bisnis penting.

## 3. Modul Utama

### 3.1 Auth & Role

Menggunakan Laravel Auth dan Spatie Laravel Permission.

Role:

- admin
- student
- parent

Redirect setelah login:

- admin: `/admin/dashboard`
- student: `/student/dashboard`
- parent: `/parent/dashboard`

### 3.2 Admin Portal

Fitur admin:

- Dashboard presensi harian.
- CRUD siswa.
- CRUD kelas.
- Registrasi wajah siswa.
- Manajemen akun siswa dan orang tua.
- Pengajuan izin/sakit.
- Hari libur.
- Pengaturan sistem.
- Laporan dan export.

### 3.3 Student Portal

Fitur siswa:

- Melihat profil.
- Melihat status presensi hari ini.
- Scan wajah untuk presensi.
- Mengajukan izin/sakit.
- Melihat riwayat presensi.

### 3.4 Parent Portal

Fitur orang tua:

- Melihat daftar anak.
- Melihat ringkasan presensi anak.
- Melihat riwayat presensi anak.
- Filter laporan per tanggal.

## 4. Service Layer

Gunakan service class untuk logika bisnis.

```text
app/Services/
├── AttendanceService.php
├── FaceRecognitionService.php
├── ReportService.php
├── HolidayService.php
└── SettingService.php
```

### AttendanceService

Bertanggung jawab untuk:

- Membuat presensi harian.
- Menentukan status Hadir/Terlambat.
- Upsert presensi berdasarkan `student_id + date`.
- Membuat status Tidak Hadir otomatis.
- Mengubah izin/sakit menjadi record presensi.

### FaceRecognitionService

Bertanggung jawab untuk:

- Menyimpan descriptor wajah.
- Membatasi maksimal 10 descriptor per siswa.
- Menghapus descriptor lama secara FIFO.
- Mengupdate flag `students.has_embedding`.
- Menyediakan descriptor untuk scanner.

### ReportService

Bertanggung jawab untuk:

- Query laporan presensi.
- Rekap berdasarkan kelas, status, dan tanggal.
- Data export Excel/PDF.

## 5. Struktur Folder

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Student/
│   │   └── Parent/
│   ├── Middleware/
│   └── Requests/
├── Livewire/
│   ├── Admin/
│   ├── Student/
│   └── Parent/
├── Models/
├── Policies/
├── Services/
└── Console/
    └── Commands/

resources/views/
├── layouts/
├── livewire/
│   ├── admin/
│   ├── student/
│   └── parent/
└── components/

database/
├── migrations/
├── seeders/
└── factories/
```

## 6. Deployment

Target deployment:

- Shared hosting yang mendukung Laravel, atau
- VPS, atau
- Laravel Forge, atau
- cPanel dengan konfigurasi Laravel.

Requirement server:

- PHP versi sesuai Laravel terbaru.
- MySQL 8+.
- Composer.
- Node.js untuk build asset.
- HTTPS wajib untuk akses kamera di browser, kecuali localhost.

## 7. Catatan Penting

- Kamera browser hanya berjalan pada HTTPS atau localhost.
- face-api.js model harus tersedia dari CDN atau folder public.
- Descriptor wajah bukan foto, tetapi tetap dianggap data sensitif.
- Foto siswa sebaiknya tidak dibuat public bebas.
- Semua fitur yang menyentuh data siswa wajib dicek role dan policy.

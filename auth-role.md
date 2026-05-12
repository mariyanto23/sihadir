# Auth dan Role HadirKu

Dokumen ini menjelaskan rancangan autentikasi dan otorisasi aplikasi HadirKu.

## 1. Stack Auth

Gunakan:

- Laravel Auth starter kit.
- Blade + Livewire.
- Spatie Laravel Permission.

Role utama:

- admin
- student
- parent

## 2. Prinsip Role

1. Role menentukan halaman yang boleh diakses user.
2. Role tidak boleh hanya dicek di tampilan.
3. Semua route wajib dilindungi middleware.
4. Akses data sensitif wajib dicek dengan policy atau query scope.
5. User student hanya boleh mengakses data siswa yang terhubung dengannya.
6. User parent hanya boleh mengakses anak yang terhubung dengannya.
7. Admin boleh mengelola semua data.

## 3. Redirect Setelah Login

Setelah login, arahkan user berdasarkan role:

```text
admin   -> /admin/dashboard
student -> /student/dashboard
parent  -> /parent/dashboard
```

Jika user tidak memiliki role valid:

- Logout otomatis, atau
- Arahkan ke halaman error akses.

## 4. Middleware Route

Contoh struktur route:

```php
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    });

Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', StudentDashboard::class)->name('dashboard');
    });

Route::middleware(['auth', 'role:parent'])
    ->prefix('parent')
    ->name('parent.')
    ->group(function () {
        Route::get('/dashboard', ParentDashboard::class)->name('dashboard');
    });
```

## 5. Data User Student

Untuk user siswa:

- `users.id` terhubung ke `students.user_id`.
- `profiles.student_id` juga boleh digunakan untuk akses cepat.
- 1 user siswa hanya boleh terhubung ke 1 siswa.

Validasi:

- Saat membuat akun siswa, pastikan siswa belum memiliki user.
- Saat menghapus akun, lepas relasi `students.user_id`.

## 6. Data User Parent

Untuk user orang tua:

- Relasi ke siswa melalui tabel `parent_student`.
- 1 parent bisa punya banyak siswa.

Validasi:

- Saat parent membuka detail siswa, cek apakah siswa tersebut ada di relasi `parent_student`.
- Jika tidak, tolak akses dengan HTTP 403.

## 7. Admin

Admin dapat:

- Mengelola siswa.
- Mengelola kelas.
- Mengelola akun.
- Mengelola izin.
- Mengelola hari libur.
- Melihat laporan.
- Mengubah setting.

Admin tidak boleh menggunakan fitur scan sebagai siswa kecuali ia juga memiliki role student dan data student valid. Untuk versi awal, satu user cukup satu role.

## 8. Policy

Buat policy untuk model penting:

```text
StudentPolicy
AttendanceRecordPolicy
LeaveRequestPolicy
```

### StudentPolicy

Aturan:

- Admin boleh semua.
- Student hanya boleh melihat data dirinya.
- Parent hanya boleh melihat anak yang terhubung.

### AttendanceRecordPolicy

Aturan:

- Admin boleh semua.
- Student hanya boleh melihat presensi dirinya.
- Parent hanya boleh melihat presensi anak yang terhubung.

### LeaveRequestPolicy

Aturan:

- Admin boleh review semua.
- Student boleh membuat dan melihat pengajuan miliknya.
- Parent boleh melihat pengajuan anak jika dibutuhkan.

## 9. Manajemen Akun oleh Admin

Admin dapat membuat:

### Akun Siswa

Input:

- Nama.
- Email.
- Password.
- Student ID.

Proses:

1. Cek siswa belum punya akun.
2. Buat user.
3. Assign role student.
4. Set `students.user_id`.
5. Buat/update profile.

### Akun Orang Tua

Input:

- Nama.
- Email.
- Password.
- Daftar student_id.

Proses:

1. Buat user.
2. Assign role parent.
3. Buat profile.
4. Insert relasi parent_student.

## 10. Reset Password

Admin dapat reset password user.

Aturan:

- Password minimal 8 karakter.
- Jangan tampilkan password lama.
- Setelah reset, tampilkan notifikasi sukses.

## 11. Keamanan Session

- Gunakan middleware auth bawaan Laravel.
- Gunakan CSRF protection bawaan Laravel.
- Logout harus invalidate session.
- Jangan simpan data sensitif di localStorage.

## 12. Seeder Role

Seeder wajib membuat role:

```php
Role::firstOrCreate(['name' => 'admin']);
Role::firstOrCreate(['name' => 'student']);
Role::firstOrCreate(['name' => 'parent']);
```

Admin default:

```text
email: admin@hadirku.test
password: password
role: admin
```

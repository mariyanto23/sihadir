# Coding Rules HadirKu

Dokumen ini berisi aturan coding untuk menjaga kualitas project HadirKu.

## 1. Prinsip Umum

1. Kode harus mudah dibaca.
2. Gunakan Bahasa Inggris untuk nama class, method, variable, dan database.
3. Gunakan Bahasa Indonesia untuk tampilan UI.
4. Jangan menaruh query kompleks di Blade.
5. Jangan menaruh logika bisnis besar di Livewire component.
6. Gunakan service class untuk proses penting.
7. Gunakan policy/middleware untuk akses data.
8. Gunakan migration untuk semua perubahan database.

## 2. Struktur Kode

Gunakan struktur:

```text
app/Models
app/Services
app/Livewire/Admin
app/Livewire/Student
app/Livewire/Parent
app/Http/Controllers
app/Http/Requests
app/Policies
app/Console/Commands
```

## 3. Naming Convention

Model:

```text
Student
AttendanceRecord
LeaveRequest
FaceDescriptor
Holiday
AppSetting
```

Service:

```text
AttendanceService
FaceRecognitionService
ReportService
HolidayService
SettingService
```

Livewire:

```text
Admin/Dashboard
Admin/Students/Index
Admin/Students/Form
Student/Dashboard
Student/FaceScanner
Parent/Dashboard
```

## 4. Database Rules

1. Setiap tabel wajib punya migration.
2. Gunakan foreign key.
3. Gunakan unique constraint untuk:
   - students.nis
   - students.user_id
   - attendance_records student_id + date
   - parent_student parent_user_id + student_id
4. Gunakan index untuk kolom yang sering difilter.
5. Jangan menyimpan role langsung sebagai string di tabel users jika memakai Spatie.

## 5. Livewire Rules

Livewire component boleh menangani:

- State form.
- Validasi ringan.
- Trigger service.
- Render data.
- Pagination.
- Filter.

Livewire component tidak boleh berisi:

- Algoritma face matching panjang.
- Logika presensi kompleks.
- Query laporan kompleks yang berulang.

Pindahkan ke service jika mulai kompleks.

## 6. Service Rules

Gunakan service untuk:

- Menentukan status presensi.
- Menjalankan auto Tidak Hadir.
- Approve izin/sakit.
- Simpan descriptor wajah.
- Generate laporan.

Contoh:

```php
$status = $attendanceService->determineStatus(now());
```

## 7. Validation Rules

Gunakan validasi Laravel.

Contoh siswa:

```php
'name' => ['required', 'string', 'max:255'],
'nis' => ['required', 'string', 'max:50', 'unique:students,nis'],
'class_id' => ['required', 'exists:classes,id'],
'photo' => ['nullable', 'image', 'max:2048'],
```

Contoh izin:

```php
'type' => ['required', 'in:Izin,Sakit'],
'start_date' => ['required', 'date'],
'end_date' => ['required', 'date', 'after_or_equal:start_date'],
'reason' => ['required', 'string', 'max:1000'],
```

## 8. Security Rules

1. Semua route dashboard wajib auth.
2. Semua route admin wajib role admin.
3. Semua route student wajib role student.
4. Semua route parent wajib role parent.
5. Gunakan policy untuk akses student dan attendance.
6. Jangan expose descriptor wajah ke user lain.
7. Jangan expose foto siswa secara bebas.
8. Jangan percaya data match dari frontend tanpa validasi user dan student.

## 9. Face Recognition Rules

1. face-api.js hanya di halaman yang membutuhkan kamera.
2. Model dimuat sekali dan tampilkan loading.
3. Pastikan hanya satu wajah saat registrasi.
4. Descriptor harus array angka.
5. Maksimal 10 descriptor per siswa.
6. Threshold default 0.55.
7. Cooldown scan 5 detik.
8. Kamera harus dihentikan ketika modal/halaman ditutup.

## 10. UI Rules

1. Gunakan Tailwind CSS.
2. Gunakan komponen Blade reusable.
3. Gunakan badge untuk status.
4. Semua tombol aksi destructive harus konfirmasi.
5. Semua form harus menampilkan error.
6. Gunakan loading state pada tombol submit.
7. UI wajib responsive.

## 11. Export Rules

Excel:

- Gunakan Laravel Excel.
- File berisi filter yang digunakan.
- Sertakan kolom tanggal, siswa, kelas, status, jam, metode.

PDF:

- Gunakan DomPDF.
- Format sederhana dan mudah dicetak.
- Sertakan nama sekolah dan rentang tanggal.

## 12. Scheduler Rules

Command auto Tidak Hadir:

- Nama: `attendance:mark-absent`.
- Jangan berjalan saat libur.
- Jangan membuat duplikat.
- Gunakan AttendanceService.
- Log jumlah siswa yang diproses.

## 13. Error Handling

Gunakan pesan ramah:

- Data berhasil disimpan.
- Data gagal disimpan.
- Anda tidak memiliki akses.
- Presensi hari ini sudah tercatat.
- Hari ini libur.
- Wajah tidak cocok.

Jangan tampilkan error teknis database ke user.

## 14. Testing Minimum

Buat test untuk:

- Admin bisa akses dashboard admin.
- Student tidak bisa akses admin.
- Parent tidak bisa akses admin.
- Student hanya bisa melihat presensi sendiri.
- Parent hanya bisa melihat anak terhubung.
- AttendanceService menentukan Hadir/Terlambat dengan benar.
- Auto Tidak Hadir tidak membuat duplikat.

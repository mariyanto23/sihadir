# API dan Flow HadirKu

Dokumen ini menjelaskan alur data, route, dan endpoint internal aplikasi HadirKu.

## 1. Prinsip Umum

1. Halaman utama menggunakan Blade + Livewire.
2. Endpoint JSON digunakan untuk fitur JavaScript seperti face-api.js.
3. Semua endpoint wajib memakai middleware auth.
4. Endpoint admin wajib role admin.
5. Endpoint siswa wajib role student.
6. Jangan expose data siswa tanpa policy.

## 2. Route Group

```text
/
/login
/logout

/admin/*
/student/*
/parent/*
```

## 3. Admin Routes

Prefix:

```text
/admin
```

Middleware:

```text
auth, role:admin
```

Route:

```text
GET  /admin/dashboard
GET  /admin/students
GET  /admin/students/{student}
POST /admin/students
PUT  /admin/students/{student}
DELETE /admin/students/{student}

POST /admin/students/{student}/photo
POST /admin/students/{student}/face-descriptors
DELETE /admin/students/{student}/face-descriptors/{descriptor}

GET /admin/classes
GET /admin/accounts
GET /admin/leave-requests
POST /admin/leave-requests/{leaveRequest}/approve
POST /admin/leave-requests/{leaveRequest}/reject

GET /admin/holidays
GET /admin/settings
GET /admin/reports
GET /admin/reports/export-excel
GET /admin/reports/export-pdf
```

## 4. Student Routes

Prefix:

```text
/student
```

Middleware:

```text
auth, role:student
```

Route:

```text
GET  /student/dashboard
GET  /student/scan
GET  /student/history
GET  /student/leave-requests
POST /student/leave-requests
GET  /student/profile

GET  /student/face-descriptors
POST /student/attendance/face-check-in
```

## 5. Parent Routes

Prefix:

```text
/parent
```

Middleware:

```text
auth, role:parent
```

Route:

```text
GET /parent/dashboard
GET /parent/children
GET /parent/children/{student}
GET /parent/children/{student}/attendance
GET /parent/profile
```

## 6. Flow Login

```text
User membuka login
-> input email/password
-> Laravel Auth validasi
-> cek role user
-> redirect sesuai role
```

Jika role admin:

```text
/admin/dashboard
```

Jika role student:

```text
/student/dashboard
```

Jika role parent:

```text
/parent/dashboard
```

## 7. Flow CRUD Siswa

```text
Admin buka /admin/students
-> Livewire load siswa dengan pagination
-> Admin tambah/edit data
-> validasi request
-> simpan ke students
-> tampilkan toast
```

Upload foto:

```text
Admin pilih foto
-> validasi image
-> simpan ke storage
-> update students.photo_path
```

## 8. Flow Registrasi Wajah

```text
Admin buka detail siswa
-> klik Registrasi Wajah
-> browser load face-api.js model
-> kamera aktif
-> wajah terdeteksi
-> descriptor dibuat
-> POST ke /admin/students/{student}/face-descriptors
-> Laravel simpan descriptor
-> Laravel update has_embedding
-> response sukses
```

Request:

```json
{
  "descriptor": [0.1, 0.2, 0.3],
  "source": "camera"
}
```

Response:

```json
{
  "message": "Descriptor wajah berhasil disimpan.",
  "has_embedding": true
}
```

## 9. Flow Presensi Wajah

```text
Siswa login
-> buka /student/scan
-> browser load model face-api.js
-> GET /student/face-descriptors
-> kamera aktif
-> ekstrak descriptor wajah saat ini
-> cocokkan dengan descriptor database di browser
-> jika match, POST /student/attendance/face-check-in
-> Laravel buat/update attendance_records
-> response status presensi
```

Request:

```json
{
  "matched": true,
  "distance": 0.43
}
```

Response sukses:

```json
{
  "message": "Presensi berhasil dicatat.",
  "status": "Hadir",
  "check_in_time": "06:21"
}
```

Response gagal:

```json
{
  "message": "Presensi gagal. Silakan coba lagi."
}
```

## 10. Flow Pengajuan Izin/Sakit

Siswa:

```text
Siswa buka form izin
-> isi type, tanggal, alasan
-> submit
-> status pending
```

Admin:

```text
Admin buka daftar pengajuan
-> approve/reject
-> jika approve, AttendanceService membuat attendance_records
```

Approve:

```text
POST /admin/leave-requests/{leaveRequest}/approve
```

Reject:

```text
POST /admin/leave-requests/{leaveRequest}/reject
```

## 11. Flow Laporan

```text
Admin buka /admin/reports
-> pilih tanggal, kelas, status
-> Livewire query data
-> tampilkan tabel
-> export Excel/PDF jika diminta
```

Filter:

- start_date
- end_date
- class_id
- status

## 12. Flow Auto Tidak Hadir

Command:

```text
php artisan attendance:mark-absent
```

Flow:

```text
Scheduler menjalankan command
-> cek hari ini libur atau bukan hari sekolah
-> ambil semua siswa aktif
-> cek attendance_records hari ini
-> jika belum ada, create Tidak Hadir
```

## 13. Response Error Standar

Gunakan pesan error sederhana:

```json
{
  "message": "Aksi tidak dapat diproses."
}
```

Untuk UI, gunakan pesan yang ramah:

- Data tidak ditemukan.
- Anda tidak memiliki akses.
- Presensi hari ini sudah tercatat.
- Hari ini libur, presensi tidak tersedia.
- Wajah belum diregistrasi.

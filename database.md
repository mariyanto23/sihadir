# Database HadirKu

Dokumen ini menjelaskan rancangan database aplikasi HadirKu berbasis MySQL.

## 1. Prinsip Database

1. Gunakan migration Laravel untuk semua perubahan struktur.
2. Jangan ubah migration lama setelah sudah dijalankan di environment bersama.
3. Gunakan foreign key untuk menjaga relasi.
4. Gunakan unique constraint untuk data penting seperti NIS dan presensi harian.
5. Gunakan enum Laravel/MySQL untuk status yang nilainya terbatas.
6. Field descriptor wajah disimpan dalam format JSON.
7. Soft delete boleh digunakan untuk siswa jika dibutuhkan, tetapi versi awal boleh hard delete dengan cascade terkontrol.

## 2. Daftar Tabel

```text
users
profiles
classes
students
parent_student
face_descriptors
attendance_records
leave_requests
holidays
app_settings
roles / permissions dari Spatie
```

## 3. Tabel `users`

Menggunakan tabel bawaan Laravel.

Tambahan opsional:

- `is_active` boolean default true.

Kolom utama:

- id
- name
- email unique
- password
- email_verified_at nullable
- remember_token
- timestamps

Role tidak disimpan langsung di `users`, tetapi memakai Spatie Laravel Permission.

## 4. Tabel `profiles`

Menyimpan informasi tambahan user.

Kolom:

- id
- user_id foreign unique
- name
- phone nullable
- student_id nullable foreign ke students
- timestamps

Catatan:

- Untuk user role student, `student_id` mengarah ke data siswa miliknya.
- Untuk parent, relasi ke anak memakai tabel pivot `parent_student`.

## 5. Tabel `classes`

Menyimpan daftar kelas.

Kolom:

- id
- name string, contoh: 1A, 1B, 2A
- level tinyInteger, contoh: 1 sampai 6
- timestamps

Index:

- unique name

Seeder awal:

- 1A sampai 6B.

## 6. Tabel `students`

Menyimpan data siswa.

Kolom:

- id
- nis string unique
- name string
- class_id foreign ke classes
- birth_date date nullable
- gender enum: L, P nullable
- photo_path string nullable
- user_id foreign ke users nullable unique
- has_embedding boolean default false
- timestamps

Constraint:

- `nis` unique.
- `user_id` unique agar 1 akun siswa hanya terhubung ke 1 siswa.
- `class_id` wajib.

Relasi:

- Student belongsTo ClassRoom/ClassModel.
- Student belongsTo User nullable.
- Student hasMany FaceDescriptor.
- Student hasMany AttendanceRecord.
- Student hasMany LeaveRequest.
- Student belongsToMany parent users melalui `parent_student`.

## 7. Tabel `parent_student`

Pivot antara orang tua dan siswa.

Kolom:

- id
- parent_user_id foreign ke users
- student_id foreign ke students
- timestamps

Constraint:

- unique parent_user_id + student_id.

Catatan:

- 1 orang tua dapat memiliki banyak anak.
- 1 siswa dapat dipantau lebih dari 1 orang tua jika diperlukan.

## 8. Tabel `face_descriptors`

Menyimpan embedding wajah siswa.

Kolom:

- id
- student_id foreign ke students
- descriptor json
- source enum: camera, upload default camera
- timestamps

Aturan:

- Maksimal 10 descriptor per siswa.
- Jika lebih dari 10, hapus descriptor paling lama.
- Jika siswa memiliki minimal 1 descriptor, `students.has_embedding = true`.
- Jika descriptor siswa kosong, `students.has_embedding = false`.

Contoh isi descriptor:

```json
[0.123, -0.034, 0.876]
```

Panjang descriptor face-api.js biasanya 128 angka.

## 9. Tabel `attendance_records`

Menyimpan presensi harian siswa.

Kolom:

- id
- student_id foreign ke students
- date date
- check_in_time time nullable
- status enum: Hadir, Terlambat, Izin, Sakit, Tidak Hadir
- method enum: face, manual, leave, auto default face
- notes text nullable
- created_by foreign ke users nullable
- timestamps

Constraint:

- unique student_id + date.

Aturan:

- 1 siswa hanya boleh memiliki 1 record presensi per hari.
- Jika scan sebelum atau sama dengan cutoff, status Hadir.
- Jika scan lewat cutoff, status Terlambat.
- Jika izin disetujui, status Izin/Sakit.
- Jika tidak scan sampai waktu tertentu, sistem dapat membuat status Tidak Hadir otomatis.

## 10. Tabel `leave_requests`

Menyimpan pengajuan izin/sakit siswa.

Kolom:

- id
- student_id foreign ke students
- type enum: Izin, Sakit
- start_date date
- end_date date
- reason text
- status enum: pending, approved, rejected default pending
- reviewed_by foreign ke users nullable
- reviewed_at timestamp nullable
- admin_notes text nullable
- timestamps

Aturan:

- `end_date` tidak boleh lebih kecil dari `start_date`.
- Jika disetujui admin, sistem membuat/mengupdate `attendance_records` untuk semua tanggal dalam range.
- Method presensi dari izin adalah `leave`.

## 11. Tabel `holidays`

Menyimpan hari libur.

Kolom:

- id
- title string
- date date
- is_recurring boolean default false
- timestamps

Aturan:

- Jika `is_recurring = true`, cocokkan berdasarkan bulan dan tanggal setiap tahun.
- Jika hari ini libur, scanner presensi dinonaktifkan.
- Dashboard menampilkan banner hari libur.

## 12. Tabel `app_settings`

Menyimpan konfigurasi aplikasi.

Kolom:

- id
- key string unique
- value json nullable
- timestamps

Contoh key:

- school_name
- school_logo
- attendance_cutoff_time
- school_days_mode
- theme_color
- pwa_name

Contoh value:

```json
"SD N 01 Jatipurwo"
```

```json
{"time":"06:30"}
```

## 13. Relasi Utama

```text
classes 1 ── * students
users 1 ── 1 profiles
users 1 ── 0..1 students
students 1 ── * face_descriptors
students 1 ── * attendance_records
students 1 ── * leave_requests
users(parent) * ── * students melalui parent_student
users(admin) 1 ── * reviewed leave_requests
```

## 14. Seeder Wajib

Seeder awal harus membuat:

1. Role:
   - admin
   - student
   - parent

2. Admin default:
   - email: admin@hadirku.test
   - password: password

3. Kelas:
   - 1A, 1B
   - 2A, 2B
   - 3A, 3B
   - 4A, 4B
   - 5A, 5B
   - 6A, 6B

4. App settings default:
   - school_name: SD N 01 Jatipurwo
   - attendance_cutoff_time: 06:30
   - school_days_mode: 6
   - theme_color: blue

## 15. Index yang Disarankan

Tambahkan index untuk:

- students.nis
- students.name
- students.class_id
- attendance_records.student_id
- attendance_records.date
- attendance_records.status
- leave_requests.status
- leave_requests.start_date
- leave_requests.end_date
- holidays.date

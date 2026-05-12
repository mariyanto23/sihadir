# Fitur Aplikasi HadirKu

Dokumen ini merangkum seluruh fitur aplikasi HadirKu berdasarkan role pengguna dan modul sistem.

## 1. Role Pengguna

Aplikasi memiliki tiga role utama:

1. Admin
2. Siswa
3. Orang Tua

Setiap role memiliki dashboard dan akses data yang berbeda.

## 2. Fitur Umum

### 2.1 Login

- User login dengan email dan password.
- Setelah login, user diarahkan sesuai role.
- User tidak boleh membuka halaman role lain.

### 2.2 Logout

- Semua user dapat logout.
- Setelah logout, user kembali ke halaman login.

### 2.3 Profil

- User dapat melihat profil.
- User dapat mengganti password.
- Data profil dasar dapat diedit sesuai role.

## 3. Fitur Admin

### 3.1 Dashboard Admin

Dashboard admin menampilkan:

- Total siswa.
- Jumlah hadir hari ini.
- Jumlah terlambat hari ini.
- Jumlah izin hari ini.
- Jumlah sakit hari ini.
- Jumlah tidak hadir hari ini.
- Daftar presensi terbaru.
- Tombol filter siswa belum hadir.
- Banner jika hari ini adalah hari libur.

### 3.2 Manajemen Kelas

Admin dapat:

- Melihat daftar kelas.
- Menambah kelas.
- Mengedit kelas.
- Menghapus kelas jika belum digunakan.

Data kelas minimal:

- Nama kelas.
- Level kelas.

### 3.3 Manajemen Siswa

Admin dapat:

- Melihat daftar siswa.
- Mencari siswa berdasarkan nama atau NIS.
- Filter siswa berdasarkan kelas.
- Menambah siswa.
- Mengedit siswa.
- Menghapus siswa.
- Upload foto siswa.
- Melihat indikator sudah/belum registrasi wajah.
- Membuka detail siswa.

Data siswa:

- NIS.
- Nama.
- Kelas.
- Tanggal lahir opsional.
- Jenis kelamin opsional.
- Foto opsional.

### 3.4 Registrasi Wajah Siswa

Admin dapat:

- Membuka kamera.
- Upload foto wajah.
- Melakukan ekstraksi descriptor wajah.
- Menyimpan descriptor ke database.
- Melihat status apakah siswa sudah punya data wajah.

Aturan:

- Maksimal 10 descriptor per siswa.
- Descriptor lama dihapus otomatis jika lebih dari 10.

### 3.5 Manajemen Akun

Admin dapat:

- Membuat akun siswa.
- Membuat akun orang tua.
- Menghubungkan akun siswa dengan data siswa.
- Menghubungkan orang tua dengan satu atau banyak siswa.
- Reset password user.
- Menonaktifkan atau menghapus akun.

Aturan:

- 1 akun siswa hanya boleh terhubung ke 1 siswa.
- 1 orang tua dapat terhubung ke banyak siswa.

### 3.6 Pengajuan Izin/Sakit

Admin dapat:

- Melihat daftar pengajuan izin/sakit.
- Filter berdasarkan status.
- Menyetujui pengajuan.
- Menolak pengajuan.
- Memberi catatan admin.

Jika pengajuan disetujui:

- Sistem otomatis membuat atau mengupdate attendance_records.
- Status menjadi Izin atau Sakit.
- Method menjadi leave.

### 3.7 Hari Libur

Admin dapat:

- Menambah hari libur.
- Menghapus hari libur.
- Membuat libur berulang tahunan.

Efek hari libur:

- Presensi dinonaktifkan.
- Banner muncul di dashboard admin, siswa, dan orang tua.
- Scheduler Tidak Hadir tidak berjalan pada hari libur.

### 3.8 Pengaturan Sistem

Admin dapat mengatur:

- Nama sekolah.
- Logo sekolah.
- Jam cutoff terlambat.
- Mode hari sekolah 5 atau 6 hari.
- Warna tema.

Default:

- Jam cutoff: 06:30.
- Mode sekolah: 6 hari.

### 3.9 Laporan

Admin dapat:

- Melihat laporan presensi.
- Filter berdasarkan tanggal.
- Filter berdasarkan kelas.
- Filter berdasarkan status.
- Export Excel.
- Export PDF.
- Melihat rekap jumlah per status.

### 3.10 Backup Sederhana

Opsional untuk versi awal:

- Export data penting ke JSON atau Excel.
- Hanya admin yang boleh mengakses.

## 4. Fitur Siswa

### 4.1 Dashboard Siswa

Dashboard siswa menampilkan:

- Nama siswa.
- Kelas.
- Foto profil.
- Status presensi hari ini.
- Countdown menuju cutoff jam masuk.
- Tombol scan wajah.
- Banner hari libur jika ada.

### 4.2 Presensi Wajah

Siswa dapat:

- Membuka kamera.
- Scan wajah.
- Sistem mencocokkan wajah dengan descriptor milik siswa.
- Jika cocok, sistem mencatat presensi.

Status otomatis:

- Hadir jika scan sebelum atau sama dengan cutoff.
- Terlambat jika scan setelah cutoff.

Aturan:

- Tidak boleh double scan dalam 5 detik.
- 1 siswa hanya punya 1 presensi per hari.
- Jika sudah presensi, tampilkan status yang sudah tercatat.

### 4.3 Pengajuan Izin/Sakit

Siswa dapat:

- Mengajukan izin.
- Mengajukan sakit.
- Mengisi tanggal mulai.
- Mengisi tanggal selesai.
- Mengisi alasan.
- Melihat status pengajuan.

Status pengajuan:

- pending
- approved
- rejected

### 4.4 Riwayat Presensi

Siswa dapat melihat:

- Tanggal.
- Jam masuk.
- Status.
- Metode.
- Catatan.

## 5. Fitur Orang Tua

### 5.1 Dashboard Orang Tua

Orang tua dapat melihat:

- Daftar anak.
- Status presensi hari ini untuk setiap anak.
- Ringkasan kehadiran.
- Tren 14 hari terakhir.

### 5.2 Detail Anak

Orang tua dapat melihat:

- Profil anak.
- Kelas anak.
- Riwayat presensi anak.
- Filter tanggal.

### 5.3 Laporan Anak

Orang tua dapat:

- Melihat laporan presensi anak.
- Filter berdasarkan rentang tanggal.
- Melihat rekap status.

Orang tua hanya boleh melihat anak yang terhubung melalui tabel `parent_student`.

## 6. Fitur Sistem Otomatis

### 6.1 Auto Tidak Hadir

Command:

```text
php artisan attendance:mark-absent
```

Fungsi:

- Berjalan setelah jam masuk, misalnya 07:00.
- Mengecek semua siswa.
- Jika siswa belum memiliki presensi hari ini, sistem membuat status Tidak Hadir.

Skip jika:

- Hari ini libur.
- Hari ini bukan hari sekolah.

### 6.2 Banner Hari Libur

Jika hari ini libur:

- Tampilkan banner di dashboard.
- Nonaktifkan tombol scan wajah.
- Jangan jalankan auto Tidak Hadir.

## 7. Prioritas Fitur Versi Awal

Urutan implementasi:

1. Auth dan role.
2. Migration dan seeder.
3. Dashboard admin sederhana.
4. CRUD kelas dan siswa.
5. Upload foto siswa.
6. Registrasi wajah.
7. Presensi wajah siswa.
8. Pengajuan izin/sakit.
9. Laporan sederhana.
10. Portal orang tua.

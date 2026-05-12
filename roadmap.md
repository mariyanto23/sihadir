# Roadmap Implementasi HadirKu

Dokumen ini berisi tahapan pengerjaan aplikasi HadirKu.

## Phase 0 — Setup Project

Tujuan:

- Project Laravel siap dijalankan.
- Database MySQL terhubung.
- Auth awal tersedia.

Task:

1. Install Laravel.
2. Setup `.env` MySQL.
3. Install Livewire starter kit.
4. Install Tailwind.
5. Install Spatie Laravel Permission.
6. Install Laravel Excel.
7. Install DomPDF.
8. Setup layout dasar.

Output:

- Halaman login berjalan.
- User bisa login/logout.
- Asset CSS/JS berjalan.

## Phase 1 — Auth dan Role

Tujuan:

- Role admin, student, parent berjalan.
- Redirect berdasarkan role.

Task:

1. Buat seeder role.
2. Buat admin default.
3. Buat middleware redirect role.
4. Buat route group admin/student/parent.
5. Buat dashboard placeholder setiap role.

Output:

- Admin masuk ke `/admin/dashboard`.
- Siswa masuk ke `/student/dashboard`.
- Orang tua masuk ke `/parent/dashboard`.
- User tidak bisa akses role lain.

## Phase 2 — Database Core

Tujuan:

- Struktur database utama selesai.

Task:

1. Migration classes.
2. Migration students.
3. Migration profiles.
4. Migration parent_student.
5. Migration face_descriptors.
6. Migration attendance_records.
7. Migration leave_requests.
8. Migration holidays.
9. Migration app_settings.
10. Seeder kelas dan settings.

Output:

- Semua tabel utama tersedia.
- Relasi model berjalan.

## Phase 3 — Layout dan UI Dasar

Tujuan:

- UI dasar siap digunakan.

Task:

1. Buat app layout.
2. Buat sidebar desktop.
3. Buat bottom nav mobile.
4. Buat komponen stat card.
5. Buat komponen status badge.
6. Buat komponen modal.
7. Buat toast notification.

Output:

- Tampilan rapi dan responsive.

## Phase 4 — Manajemen Kelas dan Siswa

Tujuan:

- Admin bisa mengelola kelas dan siswa.

Task:

1. CRUD kelas.
2. CRUD siswa.
3. Search siswa.
4. Filter siswa berdasarkan kelas.
5. Upload foto siswa.
6. Detail siswa.
7. Indikator has_embedding.

Output:

- Admin bisa mengelola data siswa lengkap.

## Phase 5 — Manajemen Akun

Tujuan:

- Admin bisa membuat akun siswa dan orang tua.

Task:

1. Form buat akun siswa.
2. Assign role student.
3. Link user ke student.
4. Form buat akun orang tua.
5. Assign role parent.
6. Link parent ke beberapa siswa.
7. Reset password.
8. Hapus/nonaktifkan akun.

Output:

- Akun siswa dan orang tua bisa digunakan login.

## Phase 6 — Face Registration

Tujuan:

- Admin bisa mendaftarkan wajah siswa.

Task:

1. Siapkan model face-api.js.
2. Buat modal kamera.
3. Load model face-api.js.
4. Deteksi wajah.
5. Ekstrak descriptor.
6. Simpan descriptor ke database.
7. Batasi 10 descriptor per siswa.
8. Update has_embedding.

Output:

- Siswa memiliki descriptor wajah.

## Phase 7 — Presensi Wajah Siswa

Tujuan:

- Siswa bisa presensi memakai wajah.

Task:

1. Dashboard siswa.
2. Status presensi hari ini.
3. Halaman scanner.
4. Ambil descriptor siswa login.
5. Match descriptor di browser.
6. Submit presensi.
7. AttendanceService menentukan Hadir/Terlambat.
8. Cooldown 5 detik.
9. Toast dan audio feedback.

Output:

- Presensi wajah berjalan end-to-end.

## Phase 8 — Izin dan Sakit

Tujuan:

- Siswa bisa mengajukan izin/sakit.
- Admin bisa approve/reject.

Task:

1. Form pengajuan siswa.
2. Riwayat pengajuan siswa.
3. List pengajuan admin.
4. Approve pengajuan.
5. Reject pengajuan.
6. Jika approve, buat attendance_records.

Output:

- Pengajuan izin/sakit terintegrasi dengan presensi.

## Phase 9 — Hari Libur dan Settings

Tujuan:

- Sistem bisa membaca hari libur dan setting sekolah.

Task:

1. CRUD hari libur.
2. Libur berulang tahunan.
3. Setting nama sekolah.
4. Setting logo.
5. Setting cutoff.
6. Setting mode 5/6 hari sekolah.
7. Banner hari libur.
8. Disable presensi saat libur.

Output:

- Presensi mengikuti jadwal sekolah dan hari libur.

## Phase 10 — Auto Tidak Hadir

Tujuan:

- Sistem otomatis menandai siswa tidak hadir.

Task:

1. Buat command `attendance:mark-absent`.
2. Cek hari libur.
3. Cek hari sekolah.
4. Loop siswa aktif.
5. Buat attendance_records Tidak Hadir jika belum ada.
6. Tambahkan scheduler.

Output:

- Siswa yang belum presensi otomatis tercatat Tidak Hadir.

## Phase 11 — Portal Orang Tua

Tujuan:

- Orang tua bisa memantau anak.

Task:

1. Dashboard parent.
2. Daftar anak.
3. Status hari ini per anak.
4. Riwayat presensi anak.
5. Filter tanggal.
6. Rekap 14 hari.

Output:

- Orang tua hanya bisa melihat anak yang terhubung.

## Phase 12 — Laporan dan Export

Tujuan:

- Admin bisa melihat dan mengunduh laporan.

Task:

1. Halaman laporan.
2. Filter tanggal.
3. Filter kelas.
4. Filter status.
5. Rekap status.
6. Export Excel.
7. Export PDF.

Output:

- Laporan presensi siap dicetak/diunduh.

## Phase 13 — Polishing dan Testing

Tujuan:

- Aplikasi stabil dan layak demo.

Task:

1. Perbaiki UI mobile.
2. Tambahkan loading state.
3. Tambahkan empty state.
4. Tambahkan konfirmasi delete.
5. Tambahkan test dasar.
6. Cek akses role.
7. Cek validasi form.
8. Cek flow presensi.
9. Update README.

Output:

- Aplikasi siap digunakan untuk demo/presentasi.

## Prioritas MVP

Jika waktu terbatas, kerjakan minimal:

1. Auth dan role.
2. CRUD siswa.
3. Registrasi wajah.
4. Presensi wajah siswa.
5. Dashboard admin.
6. Izin/sakit.
7. Laporan sederhana.

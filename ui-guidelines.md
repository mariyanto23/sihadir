# UI Guidelines HadirKu

Dokumen ini menjelaskan panduan tampilan aplikasi HadirKu.

## 1. Prinsip Desain

Aplikasi ditujukan untuk lingkungan sekolah dasar, sehingga tampilan harus:

- Bersih.
- Mudah dipahami.
- Ramah mobile.
- Tidak terlalu ramai.
- Menggunakan Bahasa Indonesia.
- Memiliki kontras yang baik.
- Tombol utama mudah ditemukan.

## 2. Teknologi UI

Gunakan:

- Blade.
- Livewire.
- Tailwind CSS.
- Alpine.js jika diperlukan.

Hindari:

- jQuery kecuali sangat diperlukan.
- Query database langsung dari Blade.
- Style inline berlebihan.

## 3. Layout

### Desktop

Gunakan:

- Sidebar kiri.
- Header atas.
- Konten utama di kanan.
- Card statistik.

### Mobile

Gunakan:

- Header ringkas.
- Bottom navigation.
- Tombol presensi sebagai tombol utama yang menonjol.
- Layout satu kolom.

## 4. Navigasi

### Admin

Menu admin:

- Dashboard
- Siswa
- Kelas
- Akun
- Izin/Sakit
- Hari Libur
- Laporan
- Pengaturan

### Siswa

Menu siswa:

- Beranda
- Scan
- Izin/Sakit
- Riwayat
- Profil

### Orang Tua

Menu orang tua:

- Beranda
- Anak
- Laporan
- Profil

## 5. Warna Status

Gunakan badge status:

| Status | Warna |
|---|---|
| Hadir | Hijau |
| Terlambat | Kuning |
| Izin | Biru |
| Sakit | Ungu |
| Tidak Hadir | Merah |
| Pending | Abu/Kuning |
| Approved | Hijau |
| Rejected | Merah |

Contoh Tailwind:

```text
Hadir: bg-green-100 text-green-700
Terlambat: bg-yellow-100 text-yellow-700
Izin: bg-blue-100 text-blue-700
Sakit: bg-purple-100 text-purple-700
Tidak Hadir: bg-red-100 text-red-700
```

## 6. Komponen UI Wajib

Buat komponen Blade reusable:

```text
resources/views/components/
├── app-layout.blade.php
├── sidebar.blade.php
├── bottom-nav.blade.php
├── stat-card.blade.php
├── status-badge.blade.php
├── modal.blade.php
├── button.blade.php
├── input.blade.php
├── select.blade.php
├── textarea.blade.php
└── empty-state.blade.php
```

## 7. Dashboard Admin

Dashboard admin harus menampilkan card:

- Total Siswa.
- Hadir.
- Terlambat.
- Izin.
- Sakit.
- Tidak Hadir.

Gunakan grid:

- Mobile: 1-2 kolom.
- Tablet: 2-3 kolom.
- Desktop: 4-6 kolom.

## 8. Halaman Scan Wajah

Halaman scan harus:

- Kamera besar dan jelas.
- Tombol scan mudah ditekan.
- Instruksi singkat.
- Feedback sukses/gagal jelas.
- Mobile portrait friendly.

Teks instruksi contoh:

```text
Pastikan wajah terlihat jelas, pencahayaan cukup, dan hanya satu wajah di kamera.
```

## 9. Form

Setiap form harus:

- Memiliki label jelas.
- Menampilkan error validasi.
- Memiliki tombol simpan dan batal.
- Disable tombol saat proses loading.

Contoh label:

- Nama Siswa
- NIS
- Kelas
- Tanggal Mulai
- Tanggal Selesai
- Alasan

## 10. Toast dan Alert

Gunakan toast untuk:

- Data berhasil disimpan.
- Presensi berhasil.
- Scan gagal.
- Validasi gagal.

Durasi toast:

- 5 sampai 10 detik.
- Sediakan tombol tutup jika memungkinkan.

## 11. Empty State

Gunakan empty state jika data kosong.

Contoh:

```text
Belum ada data siswa.
Klik tombol Tambah Siswa untuk menambahkan data baru.
```

## 12. Bahasa

Gunakan Bahasa Indonesia konsisten.

Gunakan istilah:

- Masuk
- Keluar
- Presensi
- Siswa
- Orang Tua
- Izin
- Sakit
- Terlambat
- Tidak Hadir

Hindari campuran istilah Inggris pada UI utama.

## 13. Aksesibilitas

- Semua input harus punya label.
- Tombol harus punya teks yang jelas.
- Jangan hanya mengandalkan warna untuk status.
- Gunakan ukuran font yang nyaman.
- Pastikan kontras cukup.

## 14. Responsif

Minimal breakpoint:

- Mobile: default.
- Tablet: md.
- Desktop: lg/xl.

Pastikan halaman berikut nyaman di mobile:

- Login.
- Dashboard siswa.
- Scan wajah.
- Form izin/sakit.
- Riwayat presensi.

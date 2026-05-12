# Face Recognition HadirKu

Dokumen ini menjelaskan modul pengenalan wajah pada aplikasi HadirKu.

## 1. Prinsip Utama

1. Pengenalan wajah dilakukan di browser menggunakan face-api.js.
2. Laravel tidak menjalankan model AI.
3. Laravel hanya menerima dan menyimpan descriptor wajah.
4. Descriptor wajah disimpan sebagai JSON di MySQL.
5. Kamera hanya berjalan di HTTPS atau localhost.
6. Foto siswa dan descriptor wajah dianggap data sensitif.

## 2. Library

Gunakan:

- face-api.js
- Browser MediaDevices API
- JavaScript biasa di Blade/Livewire

Model face-api.js dapat dimuat dari:

- CDN, atau
- folder `public/models/face-api/`

Untuk stabilitas produksi, lebih baik simpan model di `public/models/face-api/`.

## 3. Model yang Digunakan

Minimal gunakan:

- TinyFaceDetector
- FaceLandmark68Net
- FaceRecognitionNet

Contoh load model:

```js
await faceapi.nets.tinyFaceDetector.loadFromUri('/models/face-api');
await faceapi.nets.faceLandmark68Net.loadFromUri('/models/face-api');
await faceapi.nets.faceRecognitionNet.loadFromUri('/models/face-api');
```

## 4. Descriptor Wajah

Descriptor adalah array angka hasil ekstraksi wajah.

Karakteristik:

- Panjang umum: 128 angka.
- Format penyimpanan: JSON.
- Relasi: banyak descriptor untuk satu siswa.

Contoh:

```json
[0.012, -0.245, 0.112]
```

## 5. Registrasi Wajah

### 5.1 Aktor

- Admin.
- Siswa, jika fitur self-register diaktifkan.

Untuk versi awal, prioritaskan registrasi oleh admin.

### 5.2 Flow Registrasi

```text
Admin buka detail siswa
-> klik Registrasi Wajah
-> kamera aktif atau upload foto
-> face-api.js mendeteksi wajah
-> ekstrak descriptor
-> kirim descriptor ke Laravel
-> Laravel simpan ke face_descriptors
-> Laravel update students.has_embedding
```

### 5.3 Validasi Registrasi

Frontend:

- Pastikan hanya 1 wajah terdeteksi.
- Jika tidak ada wajah, tampilkan pesan gagal.
- Jika lebih dari 1 wajah, minta user mengulang.
- Pastikan kualitas gambar cukup terang.

Backend:

- Validasi student_id ada.
- Validasi descriptor adalah array.
- Validasi descriptor memiliki angka.
- Batasi maksimal 10 descriptor per siswa.

### 5.4 Maksimal Descriptor

Aturan:

- Maksimal 10 descriptor per siswa.
- Jika descriptor baru membuat jumlah lebih dari 10, hapus descriptor paling lama.
- Setelah insert/delete, update `students.has_embedding`.

## 6. Presensi Wajah

### 6.1 Flow Presensi Siswa

```text
Siswa login
-> buka /student/dashboard
-> klik Scan Wajah
-> kamera aktif
-> face-api.js ekstrak descriptor wajah saat ini
-> ambil descriptor milik siswa dari server
-> hitung Euclidean distance
-> jika match, kirim request presensi ke Laravel
-> Laravel tentukan status Hadir/Terlambat
-> Laravel upsert attendance_records
-> tampilkan toast dan audio feedback
```

## 7. Pencocokan Wajah

Gunakan Euclidean distance.

Formula:

```text
distance = sqrt(sum((a[i] - b[i])^2))
```

Threshold default:

```text
0.55
```

Aturan:

- Jika distance <= threshold, dianggap cocok.
- Jika ada banyak descriptor, gunakan jarak terkecil.
- Jika tidak ada descriptor, tampilkan pesan bahwa wajah belum diregistrasi.

## 8. Keamanan Presensi

Presensi hanya boleh dilakukan oleh siswa login untuk dirinya sendiri.

Backend tetap harus cek:

- User login memiliki role student.
- User terhubung ke student.
- Student memiliki descriptor.
- Hari ini bukan hari libur.
- Hari ini adalah hari sekolah.

Jangan hanya percaya hasil match dari frontend.

Catatan:

- Karena pencocokan dilakukan di frontend, sistem ini cocok untuk kebutuhan sekolah/praktikum.
- Untuk keamanan lebih tinggi, pencocokan bisa dipindah ke backend Python service, tetapi tidak diperlukan untuk versi awal.

## 9. Anti Double Scan

Frontend:

- Tambahkan cooldown 5 detik setelah scan berhasil/gagal.
- Disable tombol scan selama proses.

Backend:

- Gunakan unique constraint `student_id + date`.
- Gunakan updateOrCreate agar hanya ada 1 presensi per hari.

## 10. Penentuan Status

Cutoff default:

```text
06:30
```

Aturan:

- Jika waktu scan <= cutoff, status Hadir.
- Jika waktu scan > cutoff, status Terlambat.

Data yang disimpan:

- student_id
- date hari ini
- check_in_time saat scan
- status
- method face

## 11. Response UI

Jika berhasil:

- Toast hijau.
- Audio sukses.
- Tampilkan status hari ini.

Jika gagal:

- Toast merah/kuning.
- Audio gagal.
- Beri pesan sederhana.

Contoh pesan:

- Wajah tidak terdeteksi.
- Wajah tidak cocok.
- Wajah belum diregistrasi.
- Hari ini libur, presensi dinonaktifkan.
- Anda sudah presensi hari ini.

## 12. Livewire dan JavaScript

Livewire menangani:

- Render halaman.
- Simpan data.
- Tampilkan status.

JavaScript menangani:

- Kamera.
- Load model.
- Deteksi wajah.
- Ekstraksi descriptor.
- Perhitungan distance.

Komunikasi JS ke Livewire dapat menggunakan:

```js
Livewire.dispatch('faceDescriptorCaptured', { descriptor })
```

atau request fetch ke route Laravel.

## 13. Endpoint yang Dibutuhkan

### Simpan Descriptor

```text
POST /admin/students/{student}/face-descriptors
```

Body:

```json
{
  "descriptor": [0.1, 0.2, 0.3],
  "source": "camera"
}
```

### Ambil Descriptor Siswa Login

```text
GET /student/face-descriptors
```

Response:

```json
{
  "descriptors": [
    [0.1, 0.2, 0.3]
  ],
  "threshold": 0.55
}
```

### Submit Presensi

```text
POST /student/attendance/face-check-in
```

Body:

```json
{
  "matched": true,
  "distance": 0.43
}
```

Backend tetap harus melakukan validasi role, student, hari sekolah, dan status presensi.

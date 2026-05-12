<div class="space-y-6">
    <div class="grid gap-6 lg:grid-cols-[1fr_1.4fr]">
        <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-center gap-4">
                <div class="h-20 w-20 overflow-hidden rounded-full bg-blue-100">
                    @if ($student->photo_path)
                        <img src="{{ route('students.photo', $student) }}" class="h-full w-full object-cover" alt="{{ $student->name }}">
                    @endif
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">{{ $student->name }}</h2>
                    <p class="text-sm text-slate-500">NIS {{ $student->nis }} · Kelas {{ $student->classRoom->name }}</p>
                    <div class="mt-2"><x-status-badge :status="$student->has_embedding ? 'Wajah Terdaftar' : 'Belum Registrasi'" /></div>
                </div>
            </div>
            <div class="mt-6 grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-md bg-slate-50 p-3"><span class="block text-slate-500">Tanggal Lahir</span>{{ $student->birth_date?->format('d/m/Y') ?? '-' }}</div>
                <div class="rounded-md bg-slate-50 p-3"><span class="block text-slate-500">Jenis Kelamin</span>{{ $student->gender === 'L' ? 'Laki-laki' : ($student->gender === 'P' ? 'Perempuan' : '-') }}</div>
            </div>
        </section>

        <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="font-semibold text-slate-950">Registrasi Wajah</h2>
            <p class="mt-1 text-sm text-slate-500">Pastikan wajah terlihat jelas, pencahayaan cukup, dan hanya satu wajah di kamera.</p>
            <div class="mt-4 overflow-hidden rounded-lg bg-slate-900">
                <video id="face-video" class="aspect-video w-full object-cover" autoplay muted playsinline></video>
            </div>
            <div class="mt-4 flex flex-wrap gap-2">
                <x-button type="button" id="start-camera">Buka Kamera</x-button>
                <x-button type="button" variant="secondary" id="capture-face">Simpan Descriptor</x-button>
            </div>
            <p id="face-message" class="mt-3 text-sm font-medium text-slate-600">Model wajah belum dimuat.</p>
            <p class="mt-2 text-xs text-slate-500">Descriptor tersimpan: {{ $student->faceDescriptors->count() }} dari 10.</p>
        </section>
    </div>

    <section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-5 py-4"><h2 class="font-semibold text-slate-950">Riwayat Terakhir</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3">Tanggal</th><th>Status</th><th>Jam</th><th>Metode</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($student->attendanceRecords as $record)
                        <tr><td class="px-5 py-3">{{ $record->date->format('d/m/Y') }}</td><td><x-status-badge :status="$record->status" /></td><td>{{ $record->check_in_time ? substr($record->check_in_time, 0, 5) : '-' }}</td><td>{{ $record->method->value }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8"><x-empty-state title="Belum ada riwayat." description="Riwayat presensi siswa akan muncul di sini." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
        (() => {
            const video = document.getElementById('face-video');
            const message = document.getElementById('face-message');
            const modelUrl = 'https://justadudewhohacks.github.io/face-api.js/models';
            let loaded = false;
            let stream = null;

            async function loadModels() {
                if (loaded) return;
                message.textContent = 'Memuat model wajah...';
                await faceapi.nets.tinyFaceDetector.loadFromUri(modelUrl);
                await faceapi.nets.faceLandmark68Net.loadFromUri(modelUrl);
                await faceapi.nets.faceRecognitionNet.loadFromUri(modelUrl);
                loaded = true;
                message.textContent = 'Model siap. Buka kamera untuk registrasi.';
            }

            document.getElementById('start-camera').addEventListener('click', async () => {
                await loadModels();
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                video.srcObject = stream;
                message.textContent = 'Kamera aktif.';
            });

            document.getElementById('capture-face').addEventListener('click', async () => {
                await loadModels();
                if (!video.srcObject) {
                    message.textContent = 'Buka kamera terlebih dahulu.';
                    return;
                }
                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();
                if (detections.length !== 1) {
                    message.textContent = detections.length === 0 ? 'Wajah tidak terdeteksi.' : 'Pastikan hanya satu wajah di kamera.';
                    return;
                }
                const response = await fetch('{{ route('admin.students.face-descriptors.store', $student) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ descriptor: Array.from(detections[0].descriptor), source: 'camera' })
                });
                const data = await response.json();
                message.textContent = data.message || 'Descriptor diproses.';
                if (response.ok) setTimeout(() => location.reload(), 900);
            });

            window.addEventListener('beforeunload', () => stream?.getTracks().forEach(track => track.stop()));
        })();
    </script>
</div>

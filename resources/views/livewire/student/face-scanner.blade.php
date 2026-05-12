<div class="space-y-6">
    @if ($holiday)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-800">Hari ini libur, presensi dinonaktifkan.</div>
    @endif
    @if (! $student)
        <x-empty-state title="Akun belum terhubung." description="Hubungi admin untuk menghubungkan akun siswa." />
    @else
        <section class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <div class="mb-4">
                <h2 class="font-semibold text-slate-950">Scan Wajah</h2>
                <p class="mt-1 text-sm text-slate-500">Pastikan wajah terlihat jelas, pencahayaan cukup, dan hanya satu wajah di kamera.</p>
            </div>
            <div class="overflow-hidden rounded-lg bg-slate-900">
                <video id="scan-video" class="aspect-[3/4] w-full max-h-[70vh] object-cover sm:aspect-video" autoplay muted playsinline></video>
            </div>
            <div class="mt-4 flex flex-wrap gap-2">
                <x-button type="button" id="start-scan" @disabled($holiday || $todayRecord)>Buka Kamera</x-button>
                <x-button type="button" variant="secondary" id="capture-scan" @disabled($holiday || $todayRecord)>Scan Sekarang</x-button>
            </div>
            <p id="scan-message" class="mt-3 text-sm font-medium text-slate-600">
                {{ $todayRecord ? 'Anda sudah presensi hari ini.' : 'Model wajah belum dimuat.' }}
            </p>
            @if ($todayRecord)
                <div class="mt-3"><x-status-badge :status="$todayRecord->status" /></div>
            @endif
        </section>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
        (() => {
            const video = document.getElementById('scan-video');
            const message = document.getElementById('scan-message');
            if (!video || !message) return;

            const modelUrl = 'https://justadudewhohacks.github.io/face-api.js/models';
            let loaded = false;
            let descriptors = [];
            let threshold = 0.55;
            let cooldown = false;

            const distance = (a, b) => Math.sqrt(a.reduce((sum, value, index) => sum + Math.pow(value - b[index], 2), 0));
            const beep = (ok) => {
                const audio = new AudioContext();
                const oscillator = audio.createOscillator();
                oscillator.frequency.value = ok ? 880 : 220;
                oscillator.connect(audio.destination);
                oscillator.start();
                setTimeout(() => { oscillator.stop(); audio.close(); }, 120);
            };

            async function loadModels() {
                if (loaded) return;
                message.textContent = 'Memuat model wajah...';
                await faceapi.nets.tinyFaceDetector.loadFromUri(modelUrl);
                await faceapi.nets.faceLandmark68Net.loadFromUri(modelUrl);
                await faceapi.nets.faceRecognitionNet.loadFromUri(modelUrl);
                const response = await fetch('{{ route('student.face-descriptors') }}');
                const data = await response.json();
                descriptors = data.descriptors || [];
                threshold = data.threshold || 0.55;
                loaded = true;
                message.textContent = descriptors.length ? 'Model siap. Silakan scan wajah.' : 'Wajah belum diregistrasi.';
            }

            document.getElementById('start-scan')?.addEventListener('click', async () => {
                await loadModels();
                video.srcObject = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                message.textContent = 'Kamera aktif.';
            });

            document.getElementById('capture-scan')?.addEventListener('click', async () => {
                if (cooldown) return;
                cooldown = true;
                setTimeout(() => cooldown = false, 5000);
                await loadModels();
                if (!descriptors.length) return;
                if (!video.srcObject) {
                    message.textContent = 'Buka kamera terlebih dahulu.';
                    return;
                }
                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();
                if (detections.length !== 1) {
                    message.textContent = detections.length === 0 ? 'Wajah tidak terdeteksi.' : 'Pastikan hanya satu wajah di kamera.';
                    beep(false);
                    return;
                }
                const current = Array.from(detections[0].descriptor);
                const best = Math.min(...descriptors.map(item => distance(current, item)));
                if (best > threshold) {
                    message.textContent = 'Wajah tidak cocok.';
                    beep(false);
                    return;
                }
                const response = await fetch('{{ route('student.attendance.face-check-in') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ matched: true, distance: best })
                });
                const data = await response.json();
                message.textContent = data.message || 'Presensi diproses.';
                beep(response.ok);
                if (response.ok) setTimeout(() => location.reload(), 900);
            });
        })();
    </script>
</div>

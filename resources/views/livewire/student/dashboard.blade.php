<div class="space-y-6">
    @if ($holiday)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-800">Hari ini libur: {{ $holiday->title }}.</div>
    @endif
    @if (! $student)
        <x-empty-state title="Profil siswa belum terhubung." description="Hubungi admin untuk menghubungkan akun dengan data siswa." />
    @else
        <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-center gap-4">
                <div class="h-20 w-20 overflow-hidden rounded-full bg-blue-100">
                    @if ($student->photo_path)<img src="{{ route('students.photo', $student) }}" class="h-full w-full object-cover" alt="{{ $student->name }}">@endif
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">Halo, {{ $student->name }}</h2>
                    <p class="text-sm text-slate-500">Kelas {{ $student->classRoom->name }} · Cutoff {{ $cutoff }}</p>
                    <div class="mt-2"><x-status-badge :status="$todayRecord?->status ?? 'Belum Presensi'" /></div>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('student.scan') }}" class="inline-flex rounded-md bg-blue-700 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">Scan Wajah</a>
            </div>
        </section>
        <div class="grid gap-4 sm:grid-cols-3">
            <x-stat-card label="Status Hari Ini" :value="$todayRecord?->status->value ?? 'Belum'" tone="blue" />
            <x-stat-card label="Jam Masuk" :value="$todayRecord?->check_in_time ? substr($todayRecord->check_in_time, 0, 5) : '-'" tone="green" />
            <x-stat-card label="Wajah" :value="$student->has_embedding ? 'Terdaftar' : 'Belum'" tone="slate" />
        </div>
    @endif
</div>

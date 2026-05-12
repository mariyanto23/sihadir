<div class="space-y-6">
    @if ($holiday)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-800">
            Hari ini libur: {{ $holiday->title }}. Presensi otomatis tidak berjalan.
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-6">
        <x-stat-card label="Total Siswa" :value="$totalStudents" tone="blue" />
        <x-stat-card label="Hadir" :value="$stats['Hadir']" tone="green" />
        <x-stat-card label="Terlambat" :value="$stats['Terlambat']" tone="yellow" />
        <x-stat-card label="Izin" :value="$stats['Izin']" tone="blue" />
        <x-stat-card label="Sakit" :value="$stats['Sakit']" tone="purple" />
        <x-stat-card label="Tidak Hadir" :value="$stats['Tidak Hadir']" tone="red" />
    </div>

    <section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-950">Presensi Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr><th class="px-5 py-3">Tanggal</th><th>Siswa</th><th>Kelas</th><th>Status</th><th>Jam</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentRecords as $record)
                        <tr>
                            <td class="px-5 py-3">{{ $record->date->format('d/m/Y') }}</td>
                            <td>{{ $record->student->name }}</td>
                            <td>{{ $record->student->classRoom->name }}</td>
                            <td><x-status-badge :status="$record->status" /></td>
                            <td>{{ $record->check_in_time ? substr($record->check_in_time, 0, 5) : '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8"><x-empty-state title="Belum ada presensi." description="Presensi hari ini akan muncul di sini." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

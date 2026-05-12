<div class="space-y-6">
    <section class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <div class="grid gap-4 md:grid-cols-5">
            <x-input label="Tanggal Mulai" name="start_date" type="date" wire:model.live="start_date" />
            <x-input label="Tanggal Selesai" name="end_date" type="date" wire:model.live="end_date" />
            <x-select label="Kelas" name="class_id" wire:model.live="class_id">
                <option value="">Semua kelas</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </x-select>
            <x-select label="Status" name="status" wire:model.live="status">
                <option value="">Semua status</option>
                @foreach (['Hadir','Terlambat','Izin','Sakit','Tidak Hadir'] as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </x-select>
            <div class="flex items-end gap-2">
                <a class="rounded-md bg-green-700 px-4 py-2 text-sm font-semibold text-white" href="{{ route('admin.reports.export-excel', $this->filters()) }}">Excel</a>
                <a class="rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white" href="{{ route('admin.reports.export-pdf', $this->filters()) }}">PDF</a>
            </div>
        </div>
    </section>
    <div class="grid gap-4 sm:grid-cols-5">
        @foreach (['Hadir','Terlambat','Izin','Sakit','Tidak Hadir'] as $item)
            <x-stat-card :label="$item" :value="$recap[$item] ?? 0" tone="blue" />
        @endforeach
    </div>
    <section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3">Tanggal</th><th>Siswa</th><th>Kelas</th><th>Status</th><th>Jam</th><th>Metode</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($records as $record)
                        <tr><td class="px-5 py-3">{{ $record->date->format('d/m/Y') }}</td><td>{{ $record->student->name }}</td><td>{{ $record->student->classRoom->name }}</td><td><x-status-badge :status="$record->status" /></td><td>{{ $record->check_in_time ? substr($record->check_in_time, 0, 5) : '-' }}</td><td>{{ $record->method->value }}</td></tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8"><x-empty-state title="Tidak ada data laporan." description="Ubah filter untuk melihat data presensi." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-5 py-4">{{ $records->links() }}</div>
    </section>
</div>

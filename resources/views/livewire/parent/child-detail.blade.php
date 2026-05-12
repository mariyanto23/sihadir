<div class="space-y-6">
    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-xl font-semibold text-slate-950">{{ $student->name }}</h2>
        <p class="mt-1 text-sm text-slate-500">NIS {{ $student->nis }} · Kelas {{ $student->classRoom->name }}</p>
        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <x-input label="Tanggal Mulai" name="start_date" type="date" wire:model.live="start_date" />
            <x-input label="Tanggal Selesai" name="end_date" type="date" wire:model.live="end_date" />
        </div>
    </section>
    <section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3">Tanggal</th><th>Status</th><th>Jam</th><th>Metode</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($records as $record)
                        <tr><td class="px-5 py-3">{{ $record->date->format('d/m/Y') }}</td><td><x-status-badge :status="$record->status" /></td><td>{{ $record->check_in_time ? substr($record->check_in_time, 0, 5) : '-' }}</td><td>{{ $record->method->value }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8"><x-empty-state title="Belum ada riwayat." description="Riwayat anak akan tampil sesuai filter tanggal." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-5 py-4">{{ $records->links() }}</div>
    </section>
</div>

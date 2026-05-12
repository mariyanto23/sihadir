<section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3">Tanggal</th><th>Status</th><th>Jam Masuk</th><th>Metode</th><th>Catatan</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($records as $record)
                    <tr><td class="px-5 py-3">{{ $record->date->format('d/m/Y') }}</td><td><x-status-badge :status="$record->status" /></td><td>{{ $record->check_in_time ? substr($record->check_in_time, 0, 5) : '-' }}</td><td>{{ $record->method->value }}</td><td>{{ $record->notes ?? '-' }}</td></tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8"><x-empty-state title="Belum ada riwayat." description="Riwayat presensi akan muncul setelah scan berhasil." /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if (method_exists($records, 'links'))<div class="border-t border-slate-100 px-5 py-4">{{ $records->links() }}</div>@endif
</section>

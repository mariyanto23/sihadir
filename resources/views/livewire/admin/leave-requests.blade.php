<div class="space-y-6">
    <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <x-select label="Filter Status" name="status" wire:model.live="status">
            <option value="">Semua status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </x-select>
    </div>
    <section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3">Siswa</th><th>Tipe</th><th>Tanggal</th><th>Alasan</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($requests as $request)
                        <tr>
                            <td class="px-5 py-3 font-medium">{{ $request->student->name }}<span class="block text-xs text-slate-500">{{ $request->student->classRoom->name }}</span></td>
                            <td>{{ $request->type }}</td>
                            <td>{{ $request->start_date->format('d/m/Y') }} - {{ $request->end_date->format('d/m/Y') }}</td>
                            <td class="max-w-xs">{{ $request->reason }}</td>
                            <td><x-status-badge :status="$request->status" /></td>
                            <td>
                                @if ($request->status->value === 'pending')
                                    <button wire:click="approve({{ $request->id }})" class="font-semibold text-green-700">Setujui</button>
                                    <button wire:click="reject({{ $request->id }})" class="ml-3 font-semibold text-red-700">Tolak</button>
                                @else
                                    <span class="text-slate-400">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8"><x-empty-state title="Belum ada pengajuan." description="Pengajuan izin dan sakit siswa akan tampil di sini." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-5 py-4">{{ $requests->links() }}</div>
    </section>
</div>

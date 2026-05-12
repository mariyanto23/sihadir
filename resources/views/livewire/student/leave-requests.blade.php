<div class="grid gap-6 lg:grid-cols-[420px_1fr]">
    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="font-semibold text-slate-950">Ajukan Izin/Sakit</h2>
        <form wire:submit="submit" class="mt-4 space-y-4">
            <x-select label="Jenis Pengajuan" name="type" wire:model="type"><option value="Izin">Izin</option><option value="Sakit">Sakit</option></x-select>
            <x-input label="Tanggal Mulai" name="start_date" type="date" wire:model="start_date" />
            <x-input label="Tanggal Selesai" name="end_date" type="date" wire:model="end_date" />
            <x-textarea label="Alasan" name="reason" wire:model="reason" rows="4" />
            <x-button type="submit">Kirim Pengajuan</x-button>
        </form>
    </section>
    <section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-5 py-4"><h2 class="font-semibold text-slate-950">Riwayat Pengajuan</h2></div>
        <div class="divide-y divide-slate-100">
            @forelse ($requests as $request)
                <div class="px-5 py-4"><div class="flex items-center justify-between gap-3"><p class="font-medium">{{ $request->type }} · {{ $request->start_date->format('d/m/Y') }} - {{ $request->end_date->format('d/m/Y') }}</p><x-status-badge :status="$request->status" /></div><p class="mt-1 text-sm text-slate-500">{{ $request->reason }}</p></div>
            @empty
                <div class="p-5"><x-empty-state title="Belum ada pengajuan." description="Pengajuan yang dikirim akan tampil di sini." /></div>
            @endforelse
        </div>
    </section>
</div>

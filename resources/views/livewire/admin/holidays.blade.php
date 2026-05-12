<div class="grid gap-6 lg:grid-cols-[380px_1fr]">
    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="font-semibold text-slate-950">Tambah Hari Libur</h2>
        <form wire:submit="save" class="mt-4 space-y-4">
            <x-input label="Judul" name="title" wire:model="title" />
            <x-input label="Tanggal" name="date" type="date" wire:model="date" />
            <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" wire:model="is_recurring" class="rounded border-slate-300 text-blue-700"> Berulang tiap tahun</label>
            <x-button type="submit">Simpan</x-button>
        </form>
    </section>
    <section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3">Tanggal</th><th>Judul</th><th>Berulang</th><th class="text-right pr-5">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($holidays as $holiday)
                        <tr><td class="px-5 py-3">{{ $holiday->date->format('d/m/Y') }}</td><td class="font-medium">{{ $holiday->title }}</td><td>{{ $holiday->is_recurring ? 'Ya' : 'Tidak' }}</td><td class="pr-5 text-right"><button wire:click="delete({{ $holiday->id }})" wire:confirm="Hapus hari libur ini?" class="font-semibold text-red-700">Hapus</button></td></tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8"><x-empty-state title="Belum ada hari libur." description="Tambahkan hari libur untuk menonaktifkan presensi." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

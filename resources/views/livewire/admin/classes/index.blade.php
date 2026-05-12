<div class="grid gap-6 lg:grid-cols-[380px_1fr]">
    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="font-semibold text-slate-950">{{ $editingId ? 'Edit Kelas' : 'Tambah Kelas' }}</h2>
        <form wire:submit="save" class="mt-4 space-y-4">
            <x-input label="Nama Kelas" name="name" wire:model="name" placeholder="1A" />
            <x-input label="Level" name="level" type="number" min="1" max="6" wire:model="level" />
            <x-button type="submit">Simpan</x-button>
        </form>
    </section>
    <section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3">Kelas</th><th>Level</th><th>Siswa</th><th class="text-right pr-5">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($classes as $class)
                        <tr><td class="px-5 py-3 font-medium">{{ $class->name }}</td><td>{{ $class->level }}</td><td>{{ $class->students_count }}</td><td class="pr-5 text-right"><button wire:click="edit({{ $class->id }})" class="font-semibold text-blue-700">Edit</button><button wire:click="delete({{ $class->id }})" wire:confirm="Hapus kelas ini?" class="ml-3 font-semibold text-red-700">Hapus</button></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

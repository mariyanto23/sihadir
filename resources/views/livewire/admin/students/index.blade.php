<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="grid gap-3 sm:grid-cols-2">
            <x-input label="Cari Siswa" name="search" wire:model.live.debounce.400ms="search" placeholder="Nama atau NIS" />
            <x-select label="Filter Kelas" name="filterClass" wire:model.live="filterClass">
                <option value="">Semua kelas</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </x-select>
        </div>
        <x-button wire:click="create">Tambah Siswa</x-button>
    </div>

    <section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr><th class="px-5 py-3">Siswa</th><th>NIS</th><th>Kelas</th><th>Wajah</th><th class="text-right pr-5">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        <tr>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 overflow-hidden rounded-full bg-blue-100">
                                        @if ($student->photo_path)
                                            <img src="{{ route('students.photo', $student) }}" class="h-full w-full object-cover" alt="{{ $student->name }}">
                                        @endif
                                    </div>
                                    <span class="font-medium text-slate-900">{{ $student->name }}</span>
                                </div>
                            </td>
                            <td>{{ $student->nis }}</td>
                            <td>{{ $student->classRoom->name }}</td>
                            <td><x-status-badge :status="$student->has_embedding ? 'Terdaftar' : 'Belum'" /></td>
                            <td class="pr-5 text-right">
                                <a href="{{ route('admin.students.show', $student) }}" class="font-semibold text-blue-700">Detail</a>
                                <button wire:click="edit({{ $student->id }})" class="ml-3 font-semibold text-slate-700">Edit</button>
                                <button wire:click="delete({{ $student->id }})" wire:confirm="Hapus data siswa ini?" class="ml-3 font-semibold text-red-700">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8"><x-empty-state title="Belum ada data siswa." description="Klik Tambah Siswa untuk menambahkan data baru." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-5 py-4">{{ $students->links() }}</div>
    </section>

    <x-modal :show="$showForm" :title="$editingId ? 'Edit Siswa' : 'Tambah Siswa'">
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 md:grid-cols-2">
                <x-input label="NIS" name="nis" wire:model="nis" />
                <x-input label="Nama Siswa" name="name" wire:model="name" />
                <x-select label="Kelas" name="class_id" wire:model="class_id">
                    <option value="">Pilih kelas</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </x-select>
                <x-input label="Tanggal Lahir" name="birth_date" type="date" wire:model="birth_date" />
                <x-select label="Jenis Kelamin" name="gender" wire:model="gender">
                    <option value="">Pilih</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </x-select>
                <x-input label="Foto Siswa" name="photo" type="file" wire:model="photo" />
            </div>
            <div class="flex justify-end gap-2">
                <x-button type="button" variant="secondary" wire:click="resetForm">Batal</x-button>
                <x-button type="submit" wire:loading.attr="disabled">Simpan</x-button>
            </div>
        </form>
    </x-modal>
</div>

<div class="space-y-6">
    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="font-semibold text-slate-950">Buat Akun</h2>
        <form wire:submit="createUser" class="mt-4 grid gap-4 md:grid-cols-3">
            <x-input label="Nama" name="name" wire:model="name" />
            <x-input label="Email" name="email" type="email" wire:model="email" />
            <x-input label="Password" name="password" type="text" wire:model="password" />
            <x-select label="Role" name="role" wire:model.live="role">
                <option value="{{ \App\Support\Role::STUDENT }}">Siswa</option>
                <option value="{{ \App\Support\Role::PARENT }}">Orang Tua</option>
            </x-select>
            @if ($role === \App\Support\Role::STUDENT)
                <x-select label="Hubungkan Siswa" name="student_id" wire:model="student_id">
                    <option value="">Pilih siswa</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} - {{ $student->classRoom->name }}</option>
                    @endforeach
                </x-select>
            @else
                <label class="block md:col-span-2">
                    <span class="text-sm font-medium text-slate-700">Anak</span>
                    <select multiple wire:model="children" class="mt-1 h-32 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} - {{ $student->classRoom->name }}</option>
                        @endforeach
                    </select>
                </label>
            @endif
            <div class="flex items-end"><x-button type="submit">Buat Akun</x-button></div>
        </form>
    </section>
    <section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3">Nama</th><th>Email</th><th>Role</th><th>Status</th><th class="text-right pr-5">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $user)
                        <tr><td class="px-5 py-3 font-medium">{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->roles->pluck('name')->join(', ') }}</td><td>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td><td class="pr-5 text-right"><button wire:click="resetPassword({{ $user->id }})" class="font-semibold text-blue-700">Reset</button><button wire:click="toggle({{ $user->id }})" class="ml-3 font-semibold text-slate-700">{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-5 py-4">{{ $users->links() }}</div>
    </section>
</div>

<section class="max-w-3xl rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <form wire:submit="save" class="space-y-5">
        <div class="grid gap-4 md:grid-cols-2">
            <x-input label="Nama Sekolah" name="school_name" wire:model="school_name" />
            <x-input label="Jam Cutoff Terlambat" name="attendance_cutoff_time" type="time" wire:model="attendance_cutoff_time" />
            <x-select label="Mode Hari Sekolah" name="school_days_mode" wire:model="school_days_mode">
                <option value="5">Senin - Jumat</option>
                <option value="6">Senin - Sabtu</option>
            </x-select>
            <x-input label="Warna Tema" name="theme_color" wire:model="theme_color" />
            <x-input label="Threshold Wajah" name="face_threshold" type="number" step="0.01" min="0.3" max="0.9" wire:model="face_threshold" />
        </div>
        <x-button type="submit">Simpan Pengaturan</x-button>
    </form>
</section>

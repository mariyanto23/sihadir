<div class="space-y-6">
    @if ($holiday)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-800">Hari ini libur: {{ $holiday->title }}.</div>
    @endif
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($children as $child)
            <a href="{{ route('parent.children.show', $child) }}" class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200 hover:ring-blue-200">
                <p class="font-semibold text-slate-950">{{ $child->name }}</p>
                <p class="mt-1 text-sm text-slate-500">Kelas {{ $child->classRoom->name }}</p>
                <div class="mt-4"><x-status-badge :status="$todayRecords[$child->id]?->status ?? 'Belum Presensi'" /></div>
            </a>
        @empty
            <x-empty-state title="Belum ada anak terhubung." description="Hubungi admin untuk menghubungkan akun orang tua." />
        @endforelse
    </div>
</div>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    @forelse ($children as $child)
        <a href="{{ route('parent.children.show', $child) }}" class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200 hover:ring-blue-200">
            <p class="font-semibold text-slate-950">{{ $child->name }}</p>
            <p class="mt-1 text-sm text-slate-500">NIS {{ $child->nis }} · Kelas {{ $child->classRoom->name }}</p>
            <p class="mt-4 text-sm text-blue-700">Lihat riwayat</p>
        </a>
    @empty
        <x-empty-state title="Belum ada anak." description="Data anak yang terhubung akan tampil di sini." />
    @endforelse
</div>

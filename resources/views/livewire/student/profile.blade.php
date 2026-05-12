<section class="max-w-2xl rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <h2 class="text-lg font-semibold text-slate-950">{{ $user->name }}</h2>
    <p class="mt-1 text-sm text-slate-500">{{ $user->email }}</p>
    @if ($user->student)
        <div class="mt-6 grid gap-3 sm:grid-cols-2">
            <div class="rounded-md bg-slate-50 p-3"><span class="block text-sm text-slate-500">Nama Siswa</span>{{ $user->student->name }}</div>
            <div class="rounded-md bg-slate-50 p-3"><span class="block text-sm text-slate-500">Kelas</span>{{ $user->student->classRoom->name }}</div>
        </div>
    @endif
</section>

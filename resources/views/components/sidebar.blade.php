@php
    $role = auth()->user()?->roles->first()?->name;
    $menus = match ($role) {
        \App\Support\Role::ADMIN => [
            ['Dashboard', 'admin.dashboard'],
            ['Siswa', 'admin.students.index'],
            ['Kelas', 'admin.classes.index'],
            ['Akun', 'admin.accounts.index'],
            ['Izin/Sakit', 'admin.leave-requests.index'],
            ['Hari Libur', 'admin.holidays.index'],
            ['Laporan', 'admin.reports.index'],
            ['Pengaturan', 'admin.settings.index'],
        ],
        \App\Support\Role::STUDENT => [
            ['Beranda', 'student.dashboard'],
            ['Scan', 'student.scan'],
            ['Izin/Sakit', 'student.leave-requests.index'],
            ['Riwayat', 'student.history'],
            ['Profil', 'student.profile'],
        ],
        \App\Support\Role::PARENT => [
            ['Beranda', 'parent.dashboard'],
            ['Anak', 'parent.children.index'],
            ['Profil', 'parent.profile'],
        ],
        default => [],
    };
@endphp

<aside class="fixed inset-y-0 left-0 z-30 hidden w-72 border-r border-slate-200 bg-white lg:block">
    <div class="flex h-full flex-col">
        <div class="border-b border-slate-200 px-6 py-6">
            <div class="text-2xl font-bold text-blue-700">HadirKu</div>
            <p class="mt-1 text-sm text-slate-500">Presensi SD berbasis wajah</p>
        </div>
        <nav class="flex-1 space-y-1 px-4 py-5">
            @foreach ($menus as [$label, $route])
                <a href="{{ route($route) }}" class="block rounded-md px-4 py-3 text-sm font-medium {{ request()->routeIs($route) || request()->routeIs(Str::beforeLast($route, '.').'.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
        <div class="border-t border-slate-200 px-6 py-4 text-sm text-slate-500">
            {{ auth()->user()?->name }}
        </div>
    </div>
</aside>

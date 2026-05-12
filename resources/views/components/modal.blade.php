@props(['show' => false, 'title' => ''])
@if ($show)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
    <div class="w-full max-w-2xl rounded-lg bg-white p-6 shadow-xl">
        <h2 class="text-lg font-semibold text-slate-950">{{ $title }}</h2>
        <div class="mt-5">{{ $slot }}</div>
    </div>
</div>
@endif

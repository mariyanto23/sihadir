@props(['label', 'value', 'tone' => 'blue'])
@php
    $tones = [
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-100',
        'green' => 'bg-green-50 text-green-700 ring-green-100',
        'yellow' => 'bg-yellow-50 text-yellow-700 ring-yellow-100',
        'red' => 'bg-red-50 text-red-700 ring-red-100',
        'purple' => 'bg-purple-50 text-purple-700 ring-purple-100',
        'slate' => 'bg-slate-50 text-slate-700 ring-slate-100',
    ];
@endphp
<div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
    <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
    <p class="mt-3 text-3xl font-bold {{ $tones[$tone] ?? $tones['blue'] }} inline-flex rounded-md px-3 py-1 ring-1">{{ $value }}</p>
</div>

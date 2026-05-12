@props(['type' => 'button', 'variant' => 'primary'])
@php
    $classes = $variant === 'secondary'
        ? 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-100'
        : 'bg-blue-700 text-white hover:bg-blue-800';
@endphp
<button type="{{ $type }}" {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-semibold transition disabled:cursor-not-allowed disabled:opacity-60 {$classes}"]) }}>
    {{ $slot }}
</button>

@props(['label', 'name'])
<label class="block">
    <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
    <input name="{{ $name }}" {{ $attributes->merge(['class' => 'mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100']) }}>
    @error($name)<span class="mt-1 block text-sm text-red-600">{{ $message }}</span>@enderror
</label>

@props(['status'])
<span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ \App\Support\AttendanceLabels::badge($status instanceof \UnitEnum ? $status->value : (string) $status) }}">
    {{ $status instanceof \UnitEnum ? $status->value : $status }}
</span>

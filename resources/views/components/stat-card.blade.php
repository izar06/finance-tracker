@props([
    'label'   => '',
    'value'   => '',
    'icon'    => '📊',
    'badge'   => '',
    'color'   => 'default', // default | green | rose | blue
])

@php
$colorMap = [
    'default' => 'bg-white border border-slate-100 shadow-sm',
    'green'   => 'bg-gradient-to-br from-primary-500 to-primary-600 shadow-lg shadow-primary-500/20',
];
$isGreen = $color === 'green';
@endphp

<div class="{{ $colorMap[$color] ?? $colorMap['default'] }} rounded-2xl p-5">
    <div class="flex items-start justify-between mb-3">
        <div class="w-11 h-11 {{ $isGreen ? 'bg-white/20' : 'bg-slate-50' }} rounded-xl flex items-center justify-center">
            <span class="text-xl">{{ $icon }}</span>
        </div>
        @if($badge)
            <span class="text-xs font-semibold {{ $isGreen ? 'text-white/80 bg-white/20' : 'text-slate-500 bg-slate-100' }} px-2 py-1 rounded-full">
                {{ $badge }}
            </span>
        @endif
    </div>
    <p class="text-2xl font-bold {{ $isGreen ? 'text-white' : 'text-slate-800' }} mb-1 truncate">
        {{ $value }}
    </p>
    <p class="text-sm {{ $isGreen ? 'text-white/70' : 'text-slate-500' }}">{{ $label }}</p>
</div>

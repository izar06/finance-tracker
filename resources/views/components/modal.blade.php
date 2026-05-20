@props([
    'title'   => '',
    'maxWidth' => 'max-w-lg',
])

<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
     x-data x-on:keydown.escape.window="$dispatch('close-modal')">
    <div class="{{ $maxWidth }} w-full bg-white rounded-2xl shadow-2xl max-h-screen overflow-y-auto"
         @click.stop>

        @if($title)
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">{{ $title }}</h3>
                {{ $closeButton ?? '' }}
            </div>
        @endif

        {{ $slot }}
    </div>
</div>

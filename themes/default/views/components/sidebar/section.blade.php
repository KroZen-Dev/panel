@props([
    'title', // section title
    'name', // section identifier for state management
    'mobile' => false, // whether in mobile view
    'collapsible' => true, // whether section can collapse
])

@php
    $stateVar = "sections.{$name}";
    $storageKey = "sidebar{$name}Open";
@endphp

@if ($collapsible)
    <li
        @if (!$mobile) :class="open ? 'px-4 pt-6 pb-0' : 'px-4 pt-6 pb-0'" x-show="open" x-transition @else class="px-4 pt-6 pb-2" @endif>
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __($title) }}</h3>
            @if (!$mobile)
                <button type="button" class="p-1 hover:bg-gray-800 rounded-md text-gray-400"
                    @click="{{ $stateVar }} = !{{ $stateVar }}" :aria-expanded="{{ $stateVar }}">
                    <i class="fas fa-chevron-down transform transition-transform duration-200"
                        :class="{{ $stateVar }} ? 'rotate-180' : 'rotate-0'"></i>
                </button>
            @endif
        </div>
    </li>
@else
    <li
        @if (!$mobile) class="px-4 pt-6 pb-0" x-show="open" x-transition @else class="px-4 pt-6 pb-2" @endif>
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __($title) }}</h3>
    </li>
@endif

<ul @if (!$mobile && $collapsible) x-show="{{ $stateVar }}" x-transition @endif
    class="space-y-1 mt-2 @if (!$mobile) p-1 @endif">
    {{ $slot }}
</ul>

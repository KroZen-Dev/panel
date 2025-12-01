@props([
    'label' => null,
    'for' => null,
    'description' => null,
    'tooltip' => null,
    'required' => false,
    'inline' => false,
])

@php
    $containerClasses = $inline ? 'md:flex md:items-start md:gap-6' : 'space-y-2';
@endphp

<div {{ $attributes->class($containerClasses) }}>
    @if ($label || $description || $tooltip)
        <div class="space-y-1 {{ $inline ? 'md:w-1/3' : '' }}">
            <div class="flex items-center gap-2">
                @if ($label)
                    <label @if ($for) for="{{ $for }}" @endif
                        class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $label }}
                        @if ($required)
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                @endif
                @if ($tooltip)
                    <span data-tippy-content="{{ $tooltip }}">
                        <i
                            class="fas fa-info-circle text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 cursor-help"></i>
                    </span>
                @endif
            </div>
            @if ($description)
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
            @endif
        </div>
    @endif

    <div class="{{ $inline ? 'md:flex-1' : '' }}">
        {{ $slot }}
        @if ($for)
            @error($for)
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        @endif
    </div>
</div>

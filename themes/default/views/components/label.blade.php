@props([
    'for' => null,
    'tooltip' => null,
])

<div class="flex justify-between items-center mb-1">
    <label @if ($for) for="{{ $for }}" @endif
        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $slot }}</label>
    @if ($tooltip)
        <span data-tippy-content="{{ $tooltip }}">
            <i
                class="fas fa-info-circle text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 cursor-help"></i>
        </span>
    @endif
</div>

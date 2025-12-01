@props([
    'name' => null,
    'label' => null,
    'type' => 'text',
    'value' => null,
    'tooltip' => null,
    'prepend' => null,
])

<div class="mb-4">
    <div class="flex justify-between items-center mb-1">
        <label for="{{ $name }}"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
        @if ($tooltip)
            <span data-tippy-content="{{ $tooltip }}">
                <i
                    class="fas fa-info-circle text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 cursor-help"></i>
            </span>
        @endif
    </div>

    @if ($prepend)
        <div class="flex">
            <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">{{ $prepend }}</span>
            <input value="{{ old($name, $value) }}" id="{{ $name }}" name="{{ $name }}"
                type="{{ $type }}"
                class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 rounded-r-md shadow-sm focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-accent-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error($name) border-red-500 @enderror" />
        </div>
    @else
        <input value="{{ old($name, $value) }}" id="{{ $name }}" name="{{ $name }}"
            type="{{ $type }}"
            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-accent-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error($name) border-red-500 @enderror" />
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

@props([
    'name' => null,
    'label' => null,
    'value' => null,
    'rows' => 3,
    'tooltip' => null,
    'ckeditor' => false,
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

    <textarea rows="{{ $rows }}" id="{{ $name }}" name="{{ $name }}"
        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-accent-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error($name) border-red-500 @enderror {{ $ckeditor ? 'ckeditor' : '' }}">{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

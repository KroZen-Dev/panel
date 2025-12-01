@props([
    'name' => null,
    'label' => null,
    'value' => null,
    'tooltip' => null,
])

<div class="mb-4">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <input id="{{ $name }}" name="{{ $name }}" type="radio" value="{{ $value }}"
                @checked(old($name) == $value)
                class="h-4 w-4 text-accent-600 bg-gray-100 border-gray-300 focus:ring-accent-500 dark:focus:ring-accent-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
            <label for="{{ $name }}"
                class="ml-2 block text-sm text-gray-900 dark:text-gray-300">{{ $label }}</label>
        </div>
        @if ($tooltip)
            <span data-tippy-content="{{ $tooltip }}">
                <i
                    class="fas fa-info-circle text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 cursor-help"></i>
            </span>
        @endif
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

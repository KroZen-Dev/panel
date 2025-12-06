@props([
    'name' => null,
    'label' => null,
    'options' => [],
    'selected' => [],
    'description' => null,
    'tooltip' => null,
    'stacked' => true,
    'required' => false,
])

@php
    $fieldName = $name ? rtrim($name, '[]') : null;
    $inputName = $fieldName ? $fieldName . '[]' : null;
    $selectedValues = collect(Illuminate\Support\Arr::wrap(old($fieldName, $selected)))
        ->map(fn($value) => (string) $value)
        ->all();

    $normalizedOptions = collect($options)
        ->map(function ($option, $key) {
            if (is_array($option)) {
                return [
                    'value' => (string) ($option['value'] ?? ($option['id'] ?? $key)),
                    'label' =>
                        $option['label'] ?? ($option['name'] ?? ($option['title'] ?? ($option['value'] ?? $key))),
                    'description' => $option['description'] ?? null,
                ];
            }

            return [
                'value' => (string) (is_int($key) ? $option : $key),
                'label' => is_int($key) ? $option : $option,
                'description' => null,
            ];
        })
        ->values();
@endphp

<x-form-group :label="$label" :for="$fieldName" :description="$description" :tooltip="$tooltip" :required="$required">
    @if ($normalizedOptions->isEmpty())
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No options available.') }}</p>
    @else
        <div class="{{ $stacked ? 'space-y-2' : 'flex flex-wrap gap-3' }}">
            @foreach ($normalizedOptions as $option)
                <label
                    class="inline-flex items-start gap-2 rounded-lg border border-gray-200 bg-white/80 px-3 py-2 text-sm text-gray-700 shadow-sm transition hover:border-accent-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                    <input type="checkbox" @if ($inputName) name="{{ $inputName }}" @endif
                        value="{{ $option['value'] }}" @checked(in_array($option['value'], $selectedValues, true))
                        class="mt-1 h-4 w-4 rounded border-gray-300 text-accent-600 focus:ring-accent-500 dark:border-gray-600 dark:bg-gray-700" />
                    <span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $option['label'] }}</span>
                        @if ($option['description'])
                            <span
                                class="block text-xs text-gray-500 dark:text-gray-400">{{ $option['description'] }}</span>
                        @endif
                    </span>
                </label>
            @endforeach
        </div>
    @endif
</x-form-group>

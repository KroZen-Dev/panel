@props([
    'name' => null,
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'description' => null,
    'tooltip' => null,
    'multiple' => false,
    'required' => false,
])

@php
    $fieldName = $name ? rtrim($name, '[]') : null;
    $inputName = $multiple && $fieldName ? $fieldName . '[]' : $fieldName;

    if ($multiple) {
        $selectedValues = collect(Illuminate\Support\Arr::wrap(old($fieldName, $value)))
            ->map(fn($item) => (string) $item)
            ->all();
    } else {
        $selectedValue = (string) old($fieldName, $value ?? '');
    }

    $normalizedOptions = collect($options)
        ->map(function ($option, $key) {
            if (is_array($option)) {
                return [
                    'value' => (string) ($option['value'] ?? ($option['id'] ?? $key)),
                    'label' =>
                        $option['label'] ?? ($option['name'] ?? ($option['title'] ?? ($option['value'] ?? $key))),
                    'disabled' => $option['disabled'] ?? false,
                ];
            }

            return [
                'value' => (string) (is_int($key) ? $option : $key),
                'label' => is_int($key) ? $option : $option,
                'disabled' => false,
            ];
        })
        ->values();
@endphp

<x-form-group :label="$label" :for="$fieldName" :description="$description" :tooltip="$tooltip" :required="$required">
    <select @if ($inputName) name="{{ $inputName }}" @endif id="{{ $fieldName }}"
        @if ($multiple) multiple @endif
        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm transition focus:border-accent-500 focus:ring-2 focus:ring-accent-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
        @if ($placeholder && !$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($normalizedOptions as $option)
            <option value="{{ $option['value'] }}" @if ($multiple && in_array($option['value'], $selectedValues ?? [], true)) selected @endif
                @if (!$multiple && ($selectedValue ?? '') === $option['value']) selected @endif @if ($option['disabled']) disabled @endif>
                {{ $option['label'] }}
            </option>
        @endforeach
    </select>
</x-form-group>

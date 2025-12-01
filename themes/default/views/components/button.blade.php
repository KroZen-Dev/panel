@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
])

@php
    $baseClasses =
        'relative isolate inline-flex items-center justify-center border font-medium hover:no-underline
        focus:outline-none focus-visible:outline focus-visible:outline-offset-2 focus-visible:ring-2
        focus-visible:ring-offset-3 focus-visible:ring-offset-bg transition-all duration-200 rounded-lg';

    $primaryVariant = 'bg-accent-600 text-white border-accent-600 hover:bg-accent-700 focus-visible:ring-accent-500 dark:bg-accent-500 dark:hover:bg-accent-600 dark:border-accent-500';

    $variantClasses = match ($variant) {
        'primary' => $primaryVariant,
        'secondary' => 'bg-gray-600 text-white border-gray-600 hover:bg-gray-700 focus-visible:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600 dark:border-gray-500',
        'warning' => 'bg-warning text-white border-warning hover:bg-warning/85 focus-visible:ring-warning',
        'danger' => 'bg-danger text-white border-danger hover:bg-danger/85 focus-visible:ring-danger',
        'outline' => 'bg-transparent text-gray-700 border-gray-300 hover:bg-gray-100 focus-visible:ring-gray-500 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-800',
        'plain' => 'bg-transparent text-gray-700 border-transparent hover:bg-gray-100 focus-visible:ring-gray-500 dark:text-gray-200 dark:hover:bg-gray-800',
        default => $primaryVariant,
    };

    $sizeClasses = match ($size) {
        'xs' => 'min-h-8 gap-1.5 px-3 py-1.5 text-sm',
        'sm' => 'min-h-9 gap-1.5 px-3 py-2 text-sm',
        'md' => 'min-h-10 gap-2 px-3.5 py-2.5 text-base',
        'lg' => 'min-h-10 gap-2 px-3.5 py-3 text-lg',
        default => 'min-h-10 gap-2 px-3.5 py-2.5 text-base',
    };

    $iconClasses = 'shrink-0 self-center';
    $disabledClasses = $disabled || $loading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer';
@endphp

<button type="{{ $type }}"
    {{ $attributes->merge(['class' => "$baseClasses $variantClasses $sizeClasses $disabledClasses"]) }}
    @disabled($disabled)>

    @if ($loading)
        <svg class="{{ $iconClasses }} w-4 h-4 animate-spin order-first" ...>â€¦</svg>
    @endif

    @if ($icon)
        <i class="{{ $icon }} {{ $iconClasses }} {{ $iconPosition === 'right' ? 'order-last' : 'order-first' }}"></i>
    @endif

    <span class="order-none">{{ $slot }}</span>
</button>

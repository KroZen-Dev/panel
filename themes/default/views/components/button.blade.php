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
    $baseClasses = '
        inline-flex items-center justify-center
        font-medium transition-all duration-200
        focus:outline-none focus:ring-2
        disabled:opacity-50 disabled:cursor-not-allowed
        rounded-xl
    ';

    $variantClasses = match ($variant) {
        'primary' => 'text-white bg-accent-600/90 hover:bg-accent-500/90
             border border-accent-500/20
             shadow-md shadow-accent-500/10 hover:shadow-accent-500/20
             backdrop-blur-sm focus:ring-accent-400/40',

        'secondary' => 'text-white bg-gray-600 hover:bg-gray-500
             border border-gray-400/20
             shadow-sm backdrop-blur-sm focus:ring-gray-400/40',

        'outline' => 'text-accent-500
             border border-accent-500/30
             hover:bg-accent-500/10
             backdrop-blur-sm focus:ring-accent-500/40',

        'warning' => 'text-white bg-warning hover:bg-warning/80
             border border-warning/20
             shadow-sm focus:ring-warning/40',

        'danger' => 'text-white bg-danger hover:bg-danger/80
             border border-danger/20
             shadow-sm focus:ring-danger/40',

        'plain' => 'text-gray-300 hover:bg-gray-100/5
             border border-transparent focus:ring-gray-300/40',

        default => '',
    };

    $sizeClasses = match ($size) {
        'xs' => 'text-xs h-7 px-2.5 gap-1 rounded-md',
        'sm' => 'text-sm h-8 px-3 gap-1.5 rounded-lg',
        'md' => 'text-sm h-10 px-4 gap-2 rounded-xl',
        'lg' => 'text-base h-12 px-5 gap-2.5 rounded-2xl',
        default => 'text-sm h-10 px-4 gap-2 rounded-xl',
    };

    $iconWrapperClasses = match ($size) {
        'xs' => 'w-3.5 h-3.5',
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        default => 'w-5 h-5',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $variantClasses $sizeClasses"]) }}
    @disabled($disabled)>

    @if ($loading)
        <span class="inline-flex items-center justify-center shrink-0 {{ $iconWrapperClasses }}">
            <svg class="w-full h-full animate-spin" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.3" stroke-width="4" />
                <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" />
            </svg>
        </span>
    @endif

    @if ($icon && !$loading)
        <span
            class="inline-flex items-center justify-center shrink-0 {{ $iconWrapperClasses }}
            {{ $iconPosition === 'right' ? 'order-last' : 'order-first' }}">
            <i class="{{ $icon }}" aria-hidden="true"></i>
        </span>
    @endif

    <span>{{ $slot }}</span>
</button>

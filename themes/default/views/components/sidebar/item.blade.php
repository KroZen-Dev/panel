@props([
    'route', // route name
    'icon', // icon class e.g. "fas fa-home"
    'label', // text label
    'routePattern' => null, // optional pattern for active check
    'badge' => null, // optional badge content
    'badgeClass' => null, // optional custom badge classes
    'open' => true, // desktop collapse state
    'mobile' => false, // force mobile variant
])

@php
    $activeRoute = $routePattern ?? $route;
    $isActive = is_array($activeRoute) ? request()->routeIs(...$activeRoute) : request()->routeIs($activeRoute);

    $wrapperBase = 'flex items-center rounded-lg transition-all duration-200 group relative';

    $activeClasses = $isActive
        ? 'bg-accent-600 text-white'
        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white';

    // Desktop: uses Alpine open state
    // Mobile: always padded / always visible
    if ($mobile) {
        $padding = $badge ? 'px-3 py-2 justify-between' : 'px-3 py-2';
        $gap = 'gap-3';
    } else {
        $padding = $badge ? 'px-3 py-2 justify-between' : ($open ? 'px-3 py-2' : 'px-2 py-2 justify-center');
        $gap = '';
    }

    $iconColor = $isActive
        ? 'text-white'
        : 'text-accent-500 dark:text-accent-400 group-hover:text-accent-400 dark:group-hover:text-accent-300';

    // Default badge styling if not provided
    $defaultBadgeClass = $isActive ? 'bg-white/20' : 'bg-accent-500/20 text-accent-400';

    $finalBadgeClass = $badgeClass ?? $defaultBadgeClass;
@endphp

<a href="{{ route($route) }}" {{ $attributes->merge([
    'class' => "$wrapperBase $activeClasses $padding",
]) }}>
    <div class="flex items-center {{ $gap }}"
        @if (!$mobile) :class="{ 'justify-center w-full': !open }" @endif>
        <i class="{{ $icon }} {{ $iconColor }} w-5 text-xl transition-colors duration-200"></i>

        <span class="font-medium whitespace-nowrap transition-opacity duration-300"
            @if (!$mobile) :class="{ 'ml-3 opacity-100': open, 'ml-0 hidden': !open }" @endif>
            {{ __($label) }}
        </span>
    </div>

    @if ($badge)
        <span x-show="open" x-transition class="px-2 py-1 text-xs font-bold rounded-full {{ $finalBadgeClass }}">
            {{ $badge }}

        </span>
    @endif
</a>

<div data-slot="button-group-text"
    {{ $attributes->class("flex items-center gap-2 rounded-md border border-gray-200 bg-gray-100 px-4 text-sm font-medium text-gray-900 shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 *:data-[slot=icon]:pointer-events-none [&_[data-slot=icon]:not([class*='size-'])]:size-4") }}>
    {{ $slot }}
</div>

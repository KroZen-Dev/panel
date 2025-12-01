@props([
    'orientation' => 'horizontal',
])

@php
    $baseClasses =
        'inline-flex w-fit items-stretch rounded-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-900/20 overflow-hidden [&>*]:rounded-none [&>*]:border-0 [&>*]:shadow-none [&>*]:focus-visible:relative [&>*]:focus-visible:z-10 [&>*]:flex-1 has-[select[aria-hidden=true]:last-child]:[&>[data-slot=select-trigger]:last-of-type]:rounded-r-md [&>[data-slot=select-trigger]:not([class*="w-"])]:w-fit [&>input]:flex-1';
    $orientationClasses =
        $orientation === 'vertical'
            ? 'flex-col divide-y divide-gray-300 dark:divide-gray-600'
            : 'divide-x divide-gray-300 dark:divide-gray-600';
@endphp

<div role="group" data-slot="button-group" data-orientation="{{ $orientation }}"
    {{ $attributes->class("$baseClasses $orientationClasses") }}>
    {{ $slot }}
</div>

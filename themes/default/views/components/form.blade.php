@props([
    'method' => 'POST',
    'action' => null,
    'hasFiles' => false,
    'csrf' => true,
    'title' => null,
    'description' => null,
])

@php
    $method = strtoupper($method);
    $usesSpoofing = !in_array($method, ['GET', 'POST'], true);
    $displayMethod = $method === 'GET' ? 'GET' : 'POST';
@endphp

<form method="{{ $displayMethod }}"
    @if ($action) action="{{ $action }}" @endif
    @if ($hasFiles) enctype="multipart/form-data" @endif
    {{ $attributes->class('space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900/40') }}>
    @if ($csrf && $displayMethod !== 'GET')
        @csrf
    @endif
    @if ($usesSpoofing)
        @method($method)
    @endif

    @if ($title || $description)
        <div class="space-y-1 border-b border-gray-200 pb-4 dark:border-gray-800">
            @if ($title)
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
            @endif
            @if ($description)
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $description }}</p>
            @endif
        </div>
    @endif

    <div class="space-y-4">
        {{ $slot }}
    </div>

    @isset($actions)
        <div class="border-t border-gray-200 pt-4 dark:border-gray-800">
            {{ $actions }}
        </div>
    @endisset
</form>

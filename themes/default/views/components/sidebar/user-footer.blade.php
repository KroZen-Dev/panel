@props(['mobile' => false])

@php
    $containerClasses = $mobile
        ? 'fixed bottom-0 left-0 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-2 py-3 z-50 w-64 md:hidden'
        : 'fixed bottom-0 left-0 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-2 py-3 z-40';
    $dropdownVisibility = $mobile ? 'profileOpen' : 'profileOpen || collapsedOpen';
    $xData = $mobile ? '{ profileOpen: false }' : '{ profileOpen: false, collapsedOpen: false }';
    $clickAwayAction = $mobile ? 'profileOpen = false;' : 'profileOpen = false; collapsedOpen = false;';
@endphp

<div class="{{ $containerClasses }}"
    @if ($mobile) x-show="open" x-cloak @else :class="open ? 'w-64' : 'w-16'" @endif>
    <div x-data='{{ $xData }}' class="relative">
        @if ($mobile)
            <button @click="profileOpen = !profileOpen"
                class="w-full flex items-center gap-2 rounded-lg px-2 py-2 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors duration-200 text-left">
                <img src="{{ Auth::user()->getAvatar() }}" alt="{{ Auth::user()->name }}"
                    class="h-8 w-8 rounded-lg object-cover flex-shrink-0" />
                <div class="grid flex-1 text-left text-sm leading-tight min-w-0">
                    <span class="truncate font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                    <span class="truncate text-xs text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</span>
                </div>
                <svg class="h-4 w-4 text-gray-600 dark:text-gray-400 flex-shrink-0" fill="currentColor"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="5" r="1" />
                    <circle cx="12" cy="12" r="1" />
                    <circle cx="12" cy="19" r="1" />
                </svg>
            </button>
        @else
            <div x-show="open" x-transition>
                <button @click="profileOpen = !profileOpen"
                    class="w-full flex items-center gap-2 rounded-lg px-2 py-2 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors duration-200 text-left">
                    <img src="{{ Auth::user()->getAvatar() }}" alt="{{ Auth::user()->name }}"
                        class="h-8 w-8 rounded-lg object-cover flex-shrink-0" />
                    <div class="grid flex-1 text-left text-sm leading-tight min-w-0">
                        <span class="truncate font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                        <span class="truncate text-xs text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</span>
                    </div>
                    <svg class="h-4 w-4 text-gray-600 dark:text-gray-400 flex-shrink-0" fill="currentColor"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="5" r="1" />
                        <circle cx="12" cy="12" r="1" />
                        <circle cx="12" cy="19" r="1" />
                    </svg>
                </button>
            </div>

            <div x-show="!open" x-transition>
                <button @click="collapsedOpen = !collapsedOpen"
                    class="w-full flex items-center justify-center px-2 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors duration-200">
                    <img src="{{ Auth::user()->getAvatar() }}" alt="{{ Auth::user()->name }}"
                        class="h-8 w-8 rounded-lg object-cover" />
                </button>
            </div>
        @endif

        <div x-show="{{ $dropdownVisibility }}" @click.away="{{ $clickAwayAction }}"
            x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="absolute bottom-full left-0 mb-2 w-56 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl overflow-hidden z-50"
            style="display: none;">

            <div class="p-0 font-normal border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2 px-2 py-1.5">
                    <img src="{{ Auth::user()->getAvatar() }}" alt="{{ Auth::user()->name }}"
                        class="h-8 w-8 rounded-lg object-cover" />
                    <div class="grid flex-1 text-left text-sm leading-tight">
                        <span
                            class="truncate font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                        <span
                            class="truncate text-xs text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

            <div class="px-1 py-1">
                <a href="{{ route('profile.index') }}"
                    class="flex items-center gap-2 px-2 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors rounded-md">
                    <svg class="h-4 w-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ __('Profile') }}</span>
                </a>

                <a href="{{ route('preferences.index') }}"
                    class="flex items-center gap-2 px-2 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors rounded-md">
                    <svg class="h-4 w-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ __('Preferences') }}</span>
                </a>
            </div>

            <div class="my-1 h-px bg-gray-200 dark:bg-gray-700"></div>

            <div class="px-1 py-1">
                <a href="{{ route('notifications.index') }}"
                    class="flex items-center justify-between px-2 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors rounded-md">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span>{{ __('Notifications') }}</span>
                    </div>
                    @if (Auth::user()->unreadNotifications->count() != 0)
                        <span
                            class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-r from-warning to-danger text-xs font-bold text-white">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
            </div>

            <div class="my-1 h-px bg-gray-200 dark:bg-gray-700"></div>

            <div class="px-1 py-1">
                <form method="post" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-2 py-1.5 text-sm text-danger hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-danger/80 transition-colors rounded-md">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>{{ __('Logout') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

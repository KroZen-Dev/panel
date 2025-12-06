    <!-- Navbar -->
    <nav class="fixed top-0 z-50 bg-gray-50 dark:bg-gray-900/95 border border-gray-200 dark:border-gray-800/30 backdrop-blur-xl shadow-sm transition-all"
        x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false' }"
        @sidebar-toggle.window="sidebarOpen = $event.detail.open; localStorage.setItem('sidebarOpen', sidebarOpen)"
        :class="sidebarOpen ? 'left-0 md:left-64 right-0' : 'left-0 md:left-20 right-0'">
        <div class="flex items-center h-14 px-3">
            <!-- Left side - Brand & Toggle -->
            <div class="flex items-center space-x-3">
                <!-- Toggle Button -->
                <button
                    @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarOpen', sidebarOpen); $dispatch('sidebar-toggle', { open: sidebarOpen })"
                    class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors duration-150">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <!-- Brand Logo moved to sidebar -->
            </div>

            <!-- Center - Navbar Links -->
            <div class="hidden md:flex items-center space-x-1 ml-6 flex-1">
                <a href="{{ route('home') }}"
                    class="flex items-center px-3 py-1.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-800 transition-all duration-200">
                    <i class="fas fa-home mr-2"></i>
                    <span>{{ __('Home') }}</span>
                </a>

                @foreach ($useful_links as $link)
                    <a href="{{ $link->link }}" target="__blank"
                        class="flex items-center px-3 py-1.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-800 transition-all duration-200">
                        <i class="{{ $link->icon }} mr-2"></i>
                        <span>{{ $link->title }}</span>
                    </a>
                @endforeach
            </div>

            <!-- Right navbar links -->
            <div class="flex items-center space-x-2 ml-auto">
                @php($defaultThemeSettings = app(\App\Settings\DefaultThemeSettings::class))

                @if (!$defaultThemeSettings->force_theme_mode)
                    <!-- Theme Dropdown -->
                    <div x-data="{ open: false, isDark: document.documentElement.classList.contains('dark') }" @theme-changed.window="isDark = $event.detail.dark" class="relative">
                        <button @click="open = !open" @keydown.escape.window="open = false" x-bind:aria-expanded="open"
                            class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors duration-150 flex items-center gap-1">
                            <i class="fas" :class="isDark ? 'fa-moon text-accent-400' : 'fa-sun text-warning'"
                                style="transition: all 0.3s;"></i>
                            <i class="fas fa-chevron-down text-xs transition-all"
                                :class="[open ? 'rotate-180' : '', isDark ? 'text-gray-400' : 'text-gray-700']"
                                style="transition: transform 0.2s, color 0.3s;"></i>
                        </button>

                        <div x-show="open" x-transition:enter="ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden"
                            style="display: none;">
                            <button type="button" @click="window.setTheme('light'); open = false"
                                class="w-full flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <i class="fas fa-sun w-4 mr-2 text-warning"></i>
                                <span>{{ __('Light') }}</span>
                            </button>
                            <button type="button" @click="window.setTheme('dark'); open = false"
                                class="w-full flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <i class="fas fa-moon w-4 mr-2 text-accent-400"></i>
                                <span>{{ __('Dark') }}</span>
                            </button>
                            <div class="border-t border-gray-200 dark:border-gray-700"></div>
                            <button type="button"
                                @click="window.setTheme('{{ $defaultThemeSettings->default_theme }}'); open = false"
                                class="w-full flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <i class="fas fa-desktop w-4 mr-2 text-info"></i>
                                <span>
                                    {{ $defaultThemeSettings->default_theme === 'system' ? __('System Preference') : __('Use Default') }}
                                </span>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Credits Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="p-2 rounded-lg bg-accent-600/90 text-white hover:bg-accent-500/90 transition-colors duration-150 flex items-center gap-1">
                        <i class="fas fa-coins hidden sm:inline"></i>
                        <span class="text-sm font-medium">{{ Currency::formatForDisplay(Auth::user()->credits) }}</span>
                        <i class="fas fa-chevron-down text-xs transition-all hidden md:inline"
                            :class="open ? 'rotate-180' : ''" style="transition: transform 0.2s;"></i>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden"
                        style="display: none;">
                        <a href="{{ route('store.index') }}"
                            class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                            <i class="fas fa-coins w-4 mr-2 text-accent-400"></i>
                            <span>{{ __('Store') }}</span>
                        </a>
                        <div class="border-t border-gray-200 dark:border-gray-700"></div>
                        <a @click="open = false; $dispatch('open-redeem-modal')" href="javascript:void(0)"
                            class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors cursor-pointer">
                            <i class="fas fa-money-check-alt w-4 mr-2 text-success"></i>
                            <span>{{ __('Redeem code') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- /.navbar -->

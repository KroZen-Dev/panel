@use(App\Constants\PermissionGroups)
<!-- Main Sidebar Container -->
<!-- Main Sidebar Container -->
<aside
    class="fixed left-0 top-0 bg-gray-900 border-r border-gray-700 flex flex-col h-screen transition-all duration-200 overflow-x-hidden z-40 md:block hidden"
    x-data="{ open: localStorage.getItem('sidebarOpen') !== 'false', sections: { management: (localStorage.getItem('sidebarManagementOpen') !== 'false'), logs: (localStorage.getItem('sidebarLogsOpen') !== 'false') } }" x-init="$watch('sections.management', value => localStorage.setItem('sidebarManagementOpen', value));
    $watch('sections.logs', value => localStorage.setItem('sidebarLogsOpen', value));" @sidebar-toggle.window="open = $event.detail.open"
    :class="open ? 'w-64' : 'w-16'" x-cloak> <!-- Sidebar Content -->
    <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-900">

        <!-- Brand / Logo (moved from navbar) -->
        <div class="flex items-center" :class="open ? 'px-3 py-4' : 'px-2 py-4'">
            <a href="{{ route('home') }}" class="flex items-center w-full group"
                :class="open ? 'justify-start' : 'justify-center'">
                <img width="32" height="32"
                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->exists('icon.png') ? asset('storage/icon.png') : asset('images/ctrlpanel_logo.png') }}"
                    alt="{{ config('app.name', 'Laravel') }} Logo"
                    class="rounded-full ring-2 ring-gray-800 group-hover:ring-accent-500 transition-all duration-200">
                <span class="text-lg font-bold text-white transition-all duration-300"
                    :class="open ? 'ml-3 inline-block opacity-100' : 'hidden'">{{ config('app.name', 'CtrlPanel.gg') }}</span>
            </a>
        </div>

        <!-- Sidebar Menu -->
        <nav class="py-4" :class="open ? 'px-3' : 'px-2'">
            <ul class="space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('home') }}"
                        class="flex items-center rounded-lg transition-all duration-200 group relative @if (Request::routeIs('home')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                        :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                        <i
                            class="fas fa-home @if (Request::routeIs('home')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                        <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                            :class="open ? 'opacity-100' : 'hidden'">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                <!-- Servers -->
                <li>
                    <a href="{{ route('servers.index') }}"
                        class="flex items-center rounded-lg transition-all duration-200 group relative @if (Request::routeIs('servers.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                        :class="open ? 'px-3 py-2 justify-between' : 'px-2 py-2 justify-center'">
                        <div class="flex items-center" :class="!open && 'flex-col'">
                            <i
                                class="fas fa-server @if (Request::routeIs('servers.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                            <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                :class="open ? 'ml-3 inline-block opacity-100' : 'hidden'">{{ __('Servers') }}</span>
                        </div>
                        <span x-show="open" x-transition
                            class="px-2 py-1 text-xs font-bold rounded-full @if (Request::routeIs('servers.*')) bg-white/20 @else bg-accent-500/20 text-accent-400 @endif">
                            {{ Auth::user()->servers()->count() }}/{{ Auth::user()->server_limit }}
                        </span>
                    </a>
                </li>

                <!-- Store -->
                @if (config('app.env') == 'local' || $general_settings->store_enabled)
                    <li>
                        <a href="{{ route('store.index') }}"
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('store.*') || Request::routeIs('checkout')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                            :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                            <i
                                class="fas fa-coins @if (Request::routeIs('store.*') || Request::routeIs('checkout')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                            <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                :class="open ? 'opacity-100' : 'hidden'">{{ __('Store') }}</span>
                        </a>
                    </li>
                @endif

                <!-- Support Ticket -->
                @php($ticket_enabled = app(App\Settings\TicketSettings::class)->enabled)
                @if ($ticket_enabled)
                    @canany(PermissionGroups::TICKET_PERMISSIONS)
                        <li>
                            <a href="{{ route('ticket.index') }}"
                                class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('ticket.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                <i
                                    class="fas fa-ticket-alt @if (Request::routeIs('ticket.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                    :class="open ? 'opacity-100' : 'hidden'">{{ __('Support Ticket') }}</span>
                            </a>
                        </li>
                    @endcanany
                @endif

                <!-- Administration Section -->
                <!-- Administration Section Header -->
                @canany(array_merge(PermissionGroups::TICKET_PERMISSIONS, PermissionGroups::OVERVIEW_PERMISSIONS,
                    PermissionGroups::TICKET_ADMIN_PERMISSIONS, PermissionGroups::TICKET_BLACKLIST_PERMISSIONS,
                    PermissionGroups::ROLES_PERMISSIONS, PermissionGroups::SETTINGS_PERMISSIONS,
                    PermissionGroups::API_PERMISSIONS, PermissionGroups::USERS_PERMISSIONS,
                    PermissionGroups::SERVERS_PERMISSIONS, PermissionGroups::PRODUCTS_PERMISSIONS,
                    PermissionGroups::STORE_PERMISSIONS, PermissionGroups::VOUCHERS_PERMISSIONS,
                    PermissionGroups::PARTNERS_PERMISSIONS, PermissionGroups::COUPONS_PERMISSIONS,
                    PermissionGroups::PAYMENTS_PERMISSIONS, PermissionGroups::LOGS_PERMISSIONS))
                    <li class="px-4 pt-6 pb-0" x-show="open" x-transition>
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Administration') }}
                            </h3>
                        </div>
                    </li>
                @endcanany
                <template x-if="open">
                    <ul class="space-y-1 mt-2">
                        @canany(PermissionGroups::OVERVIEW_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.overview.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.overview.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-home @if (Request::routeIs('admin.overview.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Overview') }}</span>
                                </a>
                            </li>
                        @endcanany

                        @canany(PermissionGroups::TICKET_ADMIN_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.ticket.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.ticket.index')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-ticket-alt @if (Request::routeIs('admin.ticket.index')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Ticket List') }}</span>
                                </a>
                            </li>
                        @endcanany

                        @canany(PermissionGroups::TICKET_BLACKLIST_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.ticket.blacklist') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.ticket.blacklist')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-user-times @if (Request::routeIs('admin.ticket.blacklist')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Ticket Blacklist') }}</span>
                                </a>
                            </li>
                        @endcanany

                        @canany(PermissionGroups::ROLES_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.roles.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.roles.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-user-check @if (Request::routeIs('admin.roles.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Role Management') }}</span>
                                </a>
                            </li>
                        @endcanany

                        @canany(PermissionGroups::SETTINGS_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.settings.index') . '#icons' }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.settings.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-tools @if (Request::routeIs('admin.settings.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Settings') }}</span>
                                </a>
                            </li>
                        @endcanany

                        @canany(PermissionGroups::API_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.api.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.api.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-gamepad @if (Request::routeIs('admin.api.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Application API') }}</span>
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </template>
                @canany(array_merge(PermissionGroups::USERS_PERMISSIONS, PermissionGroups::SERVERS_PERMISSIONS,
                    PermissionGroups::PRODUCTS_PERMISSIONS, PermissionGroups::STORE_PERMISSIONS,
                    PermissionGroups::VOUCHERS_PERMISSIONS, PermissionGroups::PARTNERS_PERMISSIONS,
                    PermissionGroups::COUPONS_PERMISSIONS, PermissionGroups::USEFUL_LINKS_PERMISSIONS))
                    <li :class="sections.management ? 'px-4 pt-6 pb-0 bg-gray-900/40 rounded-md' : 'px-4 pt-6 pb-0'"
                        x-show="open" x-transition>
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Management') }}
                            </h3>
                            <button type="button" class="p-1 hover:bg-gray-800 rounded-md text-gray-400"
                                @click="sections.management = !sections.management" :aria-expanded="sections.management">
                                <i class="fas fa-chevron-down transform transition-transform duration-200"
                                    :class="sections.management ? 'rotate-180' : 'rotate-0'"></i>
                            </button>
                        </div>
                    </li>
                @endcanany
                <template x-if="open">
                    <ul x-show="sections.management" x-transition class="space-y-1 mt-2 bg-gray-900/40 rounded-md p-1">

                        @canany(PermissionGroups::USERS_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.users.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.users.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-users @if (Request::routeIs('admin.users.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Users') }}</span>
                                </a>
                            </li>
                        @endcanany

                        @canany(PermissionGroups::SERVERS_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.servers.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.servers.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-server @if (Request::routeIs('admin.servers.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300"
                                        :class="open ? 'ml-3 inline-block opacity-100' : 'hidden'">{{ __('Servers') }}</span>
                                </a>
                            </li>
                        @endcanany

                        @canany(PermissionGroups::PRODUCTS_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.products.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.products.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-sliders-h @if (Request::routeIs('admin.products.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Products') }}</span>
                                </a>
                            </li>
                        @endcanany

                        @canany(PermissionGroups::STORE_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.store.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.store.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-shopping-basket @if (Request::routeIs('admin.store.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Store') }}</span>
                                </a>
                            </li>
                        @endcanany

                        @canany(PermissionGroups::VOUCHERS_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.vouchers.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.vouchers.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-money-check-alt @if (Request::routeIs('admin.vouchers.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Vouchers') }}</span>
                                </a>
                            </li>
                        @endcanany

                        @canany(PermissionGroups::PARTNERS_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.partners.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.partners.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-handshake @if (Request::routeIs('admin.partners.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Partners') }}</span>
                                </a>
                            </li>
                        @endcanany

                        @canany(PermissionGroups::COUPONS_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.coupons.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.coupons.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-ticket-alt @if (Request::routeIs('admin.coupons.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Coupons') }}</span>
                                </a>
                            </li>
                        @endcanany
                        <!-- Administration links removed from Management list (now in separate Administration section) -->

                        @canany(PermissionGroups::USEFUL_LINKS_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.usefullinks.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.usefullinks.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-link @if (Request::routeIs('admin.usefullinks.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Useful Links') }}</span>
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </template>

                <!-- Other links merged into Management section -->

                <!-- Logs Section Header -->
                @canany(array_merge(PermissionGroups::PAYMENTS_PERMISSIONS, PermissionGroups::LOGS_PERMISSIONS))
                    <li :class="sections.logs ? 'px-4 pt-6 pb-0 bg-gray-900/40 rounded-md' : 'px-4 pt-6 pb-0'"
                        x-show="open" x-transition>
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Logs') }}
                            </h3>
                            <button type="button" class="p-1 hover:bg-gray-800 rounded-md text-gray-400"
                                @click="sections.logs = !sections.logs" :aria-expanded="sections.logs">
                                <i class="fas fa-chevron-down transform transition-transform duration-200"
                                    :class="sections.logs ? 'rotate-180' : 'rotate-0'"></i>
                            </button>
                        </div>
                    </li>
                @endcanany
                <template x-if="open">
                    <ul x-show="sections.logs" x-transition class="space-y-1 mt-2 bg-gray-900/40 rounded-md p-1">

                        @canany(PermissionGroups::PAYMENTS_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.payments.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.payments.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2 justify-between' : 'px-2 py-2 justify-center'">
                                    <div class="flex items-center" :class="!open && 'flex-col'">
                                        <i
                                            class="fas fa-money-bill-wave @if (Request::routeIs('admin.payments.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                        <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                            :class="open ? 'ml-3 inline-block opacity-100' : 'hidden'">{{ __('Payments') }}</span>
                                    </div>
                                    <span x-show="open" x-transition
                                        class="px-2 py-1 text-xs font-bold rounded-full @if (Request::routeIs('admin.payments.*')) bg-white/20 @else @endif"></span>
                                </a>
                            </li>
                        @endcanany

                        @canany(PermissionGroups::LOGS_PERMISSIONS)
                            <li>
                                <a href="{{ route('admin.activitylogs.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.activitylogs.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                    :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                    <i
                                        class="fas fa-clipboard-list @if (Request::routeIs('admin.activitylogs.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                        :class="open ? 'opacity-100' : 'hidden'">{{ __('Activity Logs') }}</span>
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </template>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<!-- Mobile Sidebar Overlay -->
<div x-show="open" x-cloak class="fixed inset-0 z-50 bg-black/20 md:hidden" @click="open = false">
    <div class="fixed left-0 top-0 h-full w-64 bg-gray-900 border-r border-gray-700 transform transition-transform duration-200 ease-in-out"
        :class="open ? 'translate-x-0' : '-translate-x-full'">
        <!-- Sidebar Content -->
        <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-900">
            <!-- Brand / Logo -->
            <div class="flex items-center px-3 py-4">
                <a href="{{ route('home') }}" class="flex items-center w-full group">
                    <img width="32" height="32"
                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->exists('icon.png') ? asset('storage/icon.png') : asset('images/ctrlpanel_logo.png') }}"
                        alt="{{ config('app.name', 'Laravel') }} Logo"
                        class="rounded-full ring-2 ring-gray-800 group-hover:ring-accent-500 transition-all duration-200">
                    <span
                        class="text-lg font-bold text-white transition-all duration-300">{{ config('app.name', 'CtrlPanel.gg') }}</span>
                </a>
            </div>
            <!-- Sidebar Menu -->
            <nav class="py-4 px-3">
                <ul class="space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('home') }}"
                            class="flex items-center rounded-lg transition-all duration-200 group relative @if (Request::routeIs('home')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif px-3 py-2">
                            <i
                                class="fas fa-home @if (Request::routeIs('home')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                            <span class="ml-3 font-medium">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                    <!-- Servers -->
                    <li>
                        <a href="{{ route('servers.index') }}"
                            class="flex items-center rounded-lg transition-all duration-200 group relative @if (Request::routeIs('servers.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif px-3 py-2 justify-between">
                            <div class="flex items-center">
                                <i
                                    class="fas fa-server @if (Request::routeIs('servers.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                <span class="ml-3 font-medium">{{ __('Servers') }}</span>
                            </div>
                            <span
                                class="px-2 py-1 text-xs font-bold rounded-full @if (Request::routeIs('servers.*')) bg-white/20 @else bg-accent-500/20 text-accent-400 @endif">
                                {{ Auth::user()->servers()->count() }}/{{ Auth::user()->server_limit }}
                            </span>
                        </a>
                    </li>
                    <!-- Store -->
                    @if (config('app.env') == 'local' || $general_settings->store_enabled)
                        <li>
                            <a href="{{ route('store.index') }}"
                                class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('store.*') || Request::routeIs('checkout')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif px-3 py-2">
                                <i
                                    class="fas fa-coins @if (Request::routeIs('store.*') || Request::routeIs('checkout')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                <span class="ml-3 font-medium">{{ __('Store') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- Support Ticket -->
                    @php($ticket_enabled = app(App\Settings\TicketSettings::class)->enabled)
                    @if ($ticket_enabled)
                        @canany(PermissionGroups::TICKET_PERMISSIONS)
                            <li>
                                <a href="{{ route('ticket.index') }}"
                                    class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('ticket.*')) bg-accent-500 text-white @else text-gray-300 hover:bg-gray-800 hover:text-white @endif px-3 py-2">
                                    <i
                                        class="fas fa-ticket-alt @if (Request::routeIs('ticket.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                    <span class="ml-3 font-medium">{{ __('Support Ticket') }}</span>
                                </a>
                            </li>
                        @endcanany
                    @endif
                    <!-- Add more items as needed, but for brevity, stopping here -->
                </ul>
            </nav>
        </div>
    </div>
</div>

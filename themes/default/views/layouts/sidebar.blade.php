@use(App\Constants\PermissionGroups)
<!-- Main Sidebar Container -->
<aside
    class="fixed left-0 top-0 bg-gradient-to-b from-gray-900  to-gray-800 border-r border-gray-700/50 flex flex-col h-screen transition-all duration-100 overflow-x-hidden z-40"
    x-data="{ open: localStorage.getItem('sidebarOpen') !== 'false' }" @sidebar-toggle.window="open = $event.detail.open" :class="open ? 'w-64' : 'w-20'" x-cloak>

    <!-- Sidebar Content -->
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
                        class="flex items-center rounded-lg transition-all duration-200 group relative @if (Request::routeIs('home')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                        class="flex items-center rounded-lg transition-all duration-200 group relative @if (Request::routeIs('servers.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('store.*') || Request::routeIs('checkout')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                                class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('ticket.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                                :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                                <i
                                    class="fas fa-ticket-alt @if (Request::routeIs('ticket.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                    :class="open ? 'opacity-100' : 'hidden'">{{ __('Support Ticket') }}</span>
                            </a>
                        </li>
                    @endcanany
                @endif

                <!-- Administration Section Header -->
                @canany(array_merge(PermissionGroups::TICKET_PERMISSIONS, PermissionGroups::OVERVIEW_PERMISSIONS,
                    PermissionGroups::TICKET_ADMIN_PERMISSIONS, PermissionGroups::TICKET_BLACKLIST_PERMISSIONS,
                    PermissionGroups::ROLES_PERMISSIONS, PermissionGroups::SETTINGS_PERMISSIONS,
                    PermissionGroups::API_PERMISSIONS, PermissionGroups::USERS_PERMISSIONS,
                    PermissionGroups::SERVERS_PERMISSIONS, PermissionGroups::PRODUCTS_PERMISSIONS,
                    PermissionGroups::STORE_PERMISSIONS, PermissionGroups::VOUCHERS_PERMISSIONS,
                    PermissionGroups::PARTNERS_PERMISSIONS, PermissionGroups::COUPONS_PERMISSIONS,
                    PermissionGroups::USEFUL_LINKS_PERMISSIONS, PermissionGroups::PAYMENTS_PERMISSIONS,
                    PermissionGroups::LOGS_PERMISSIONS))
                    <li x-show="open" x-transition class="px-4 pt-6 pb-2">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Administration') }}
                        </h3>
                    </li>
                @endcanany

                @canany(PermissionGroups::OVERVIEW_PERMISSIONS)
                    <li>
                        <a href="{{ route('admin.overview.index') }}"
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.overview.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.ticket.index')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.ticket.blacklist')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.roles.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.settings.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.api.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                            :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                            <i
                                class="fas fa-gamepad @if (Request::routeIs('admin.api.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                            <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                :class="open ? 'opacity-100' : 'hidden'">{{ __('Application API') }}</span>
                        </a>
                    </li>
                @endcanany

                <!-- Management Section Header -->
                @canany(array_merge(PermissionGroups::USERS_PERMISSIONS, PermissionGroups::SERVERS_PERMISSIONS,
                    PermissionGroups::PRODUCTS_PERMISSIONS))
                    <li x-show="open" x-transition class="px-4 pt-6 pb-2">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Management') }}</h3>
                    </li>
                @endcanany

                @canany(PermissionGroups::USERS_PERMISSIONS)
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.users.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.servers.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.products.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.store.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.vouchers.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.partners.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
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
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.coupons.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                            :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                            <i
                                class="fas fa-ticket-alt @if (Request::routeIs('admin.coupons.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                            <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                :class="open ? 'opacity-100' : 'hidden'">{{ __('Coupons') }}</span>
                        </a>
                    </li>
                @endcanany

                <!-- Other Section Header -->
                @canany(PermissionGroups::USEFUL_LINKS_PERMISSIONS)
                    <li x-show="open" x-transition class="px-4 pt-6 pb-2">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Other') }}</h3>
                    </li>
                @endcanany

                @canany(PermissionGroups::USEFUL_LINKS_PERMISSIONS)
                    <li>
                        <a href="{{ route('admin.usefullinks.index') }}"
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.usefullinks.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                            :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                            <i
                                class="fas fa-link @if (Request::routeIs('admin.usefullinks.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                            <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                :class="open ? 'opacity-100' : 'hidden'">{{ __('Useful Links') }}</span>
                        </a>
                    </li>
                @endcanany

                <!-- Logs Section Header -->
                @canany(array_merge(PermissionGroups::PAYMENTS_PERMISSIONS, PermissionGroups::LOGS_PERMISSIONS))
                    <li x-show="open" x-transition class="px-4 pt-6 pb-2">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Logs') }}</h3>
                    </li>
                @endcanany

                @canany(PermissionGroups::PAYMENTS_PERMISSIONS)
                    <li>
                        <a href="{{ route('admin.payments.index') }}"
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.payments.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                            :class="open ? 'px-3 py-2 justify-between' : 'px-2 py-2 justify-center'">
                            <div class="flex items-center" :class="!open && 'flex-col'">
                                <i
                                    class="fas fa-money-bill-wave @if (Request::routeIs('admin.payments.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                                <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                    :class="open ? 'ml-3 inline-block opacity-100' : 'hidden'">{{ __('Payments') }}</span>
                            </div>
                            <span x-show="open" x-transition
                                class="px-2 py-1 text-xs font-bold rounded-full @if (Request::routeIs('admin.payments.*')) bg-white/20 @else @endif"
                                @if (!Request::routeIs('admin.payments.*')) style="background-color: rgb(var(--success) / 0.2); color: rgb(var(--success));" @endif>
                                {{ \App\Models\Payment::count() }}
                            </span>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::LOGS_PERMISSIONS)
                    <li>
                        <a href="{{ route('admin.activitylogs.index') }}"
                            class="flex items-center rounded-lg transition-all duration-200 group @if (Request::routeIs('admin.activitylogs.*')) bg-gradient-to-r from-accent-600 to-accent-500 text-white shadow-lg shadow-accent-500/50 @else text-gray-300 hover:bg-gray-800 hover:text-white @endif"
                            :class="open ? 'px-3 py-2' : 'px-2 py-2 justify-center'">
                            <i
                                class="fas fa-clipboard-list @if (Request::routeIs('admin.activitylogs.*')) text-white @else text-accent-400 group-hover:text-accent-300 @endif w-5 text-xl transition-colors duration-200"></i>
                            <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-300"
                                :class="open ? 'opacity-100' : 'hidden'">{{ __('Activity Logs') }}</span>
                        </a>
                    </li>
                @endcanany
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

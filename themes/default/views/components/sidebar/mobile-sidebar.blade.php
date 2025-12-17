@use(App\Constants\PermissionGroups)
<!-- Mobile Sidebar -->
<!-- Backdrop -->
<div x-data="{ open: localStorage.getItem('sidebarOpen') !== 'false' }" @sidebar-toggle.window="open = $event.detail.open" x-cloak x-show="open"
    x-transition:enter="transition ease-in-out duration-150" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 md:hidden"
    @click="open = false; $dispatch('sidebar-toggle', { open: false })">
</div>
<aside
    class="fixed inset-y-0 z-20 flex flex-col flex-shrink-0 w-64 mt-16 bg-gray-50 dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 shadow-2xl md:hidden"
    x-data="{ open: localStorage.getItem('sidebarOpen') !== 'false' }" @sidebar-toggle.window="open = $event.detail.open" x-cloak x-show="open"
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 transform -translate-x-full" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 transform -translate-x-full"
    @click.away="open = false; $dispatch('sidebar-toggle', { open: false })"
    @keydown.escape="open = false; $dispatch('sidebar-toggle', { open: false })">
    <!-- Sidebar Content -->
    <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-900 pb-20">
        <!-- Brand / Logo -->
        <div class="flex items-center px-3 py-4 relative">
            <a href="{{ route('home') }}" class="flex items-center w-full group">
                <img width="32" height="32"
                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->exists('icon.png') ? asset('storage/icon.png') : asset('images/ctrlpanel_logo.png') }}"
                    alt="{{ config('app.name', 'Laravel') }} Logo"
                    class="rounded-full ring-2 ring-gray-800 group-hover:ring-accent-500 transition-all duration-200">
                <span
                    class="text-lg font-bold text-gray-900 dark:text-white transition-all duration-300 ml-3">{{ config('app.name', 'CtrlPanel.gg') }}</span>
            </a>
        </div>
        <!-- Sidebar Menu -->
        <nav class="py-4 px-3" @click="open = false; $dispatch('sidebar-toggle', { open: false })">
            <ul class="space-y-1">
                <!-- Dashboard -->
                <x-sidebar.item label="Dashboard" icon="fas fa-home" :route="'home'" :route-pattern="'home'"
                    mobile="true" />
                <!-- Servers -->
                <x-sidebar.item label="Servers" icon="fas fa-server" route="servers.index" route-pattern="servers.*"
                    :badge="Auth::user()->servers()->count() . '/' . Auth::user()->server_limit" mobile="true" />


                <!-- Store -->
                @if (config('app.env') == 'local' || $general_settings->store_enabled)
                    <x-sidebar.item label="Store" icon="fas fa-coins" route="store.index" :route-pattern="['store.*', 'checkout']"
                        mobile="true" />
                @endif

                <!-- Support Ticket -->
                @php($ticket_enabled = app(App\Settings\TicketSettings::class)->enabled)
                @if ($ticket_enabled)
                    @canany(PermissionGroups::TICKET_PERMISSIONS)
                        <x-sidebar.item label="Support Ticket" icon="fas fa-plus-circle" route="ticket.index"
                            :route-pattern="'ticket.*'" mobile="true" />
                    @endcanany
                @endif

                <!-- Administration Section -->
                @canany(array_merge(PermissionGroups::TICKET_PERMISSIONS, PermissionGroups::OVERVIEW_PERMISSIONS,
                    PermissionGroups::TICKET_ADMIN_PERMISSIONS, PermissionGroups::TICKET_BLACKLIST_PERMISSIONS,
                    PermissionGroups::ROLES_PERMISSIONS, PermissionGroups::SETTINGS_PERMISSIONS,
                    PermissionGroups::API_PERMISSIONS, PermissionGroups::USERS_PERMISSIONS,
                    PermissionGroups::SERVERS_PERMISSIONS, PermissionGroups::PRODUCTS_PERMISSIONS,
                    PermissionGroups::STORE_PERMISSIONS, PermissionGroups::VOUCHERS_PERMISSIONS,
                    PermissionGroups::PARTNERS_PERMISSIONS, PermissionGroups::COUPONS_PERMISSIONS,
                    PermissionGroups::PAYMENTS_PERMISSIONS, PermissionGroups::LOGS_PERMISSIONS))
                    <x-sidebar.section title="Administration" name="administration" mobile="true" :collapsible="false">

                        @canany(PermissionGroups::OVERVIEW_PERMISSIONS)
                            <x-sidebar.item label="Overview" icon="fas fa-home" route="admin.overview.index" :route-pattern="'admin.overview.*'"
                                mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::TICKET_ADMIN_PERMISSIONS)
                            <x-sidebar.item label="Ticket List" icon="fas fa-ticket-alt" route="admin.ticket.index"
                                :route-pattern="'admin.ticket.*'" mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::TICKET_BLACKLIST_PERMISSIONS)
                            <x-sidebar.item label="Ticket Blacklist" icon="fas fa-user-times" route="admin.ticket.blacklist"
                                :route-pattern="'admin.ticket.blacklist'" mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::ROLES_PERMISSIONS)
                            <x-sidebar.item label="Role Management" icon="fas fa-user-check" route="admin.roles.index"
                                :route-pattern="'admin.roles.*'" mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::SETTINGS_PERMISSIONS)
                            <x-sidebar.item label="Settings" icon="fas fa-tools" route="admin.settings.index" :route-pattern="'admin.settings.*'"
                                mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::API_PERMISSIONS)
                            <x-sidebar.item label="Application API" icon="fas fa-gamepad" route="admin.api.index"
                                :route-pattern="'admin.api.*'" mobile="true" />
                        @endcanany
                    </x-sidebar.section>
                @endcanany

                <!-- Management Section -->
                @canany(array_merge(PermissionGroups::USERS_PERMISSIONS, PermissionGroups::SERVERS_PERMISSIONS,
                    PermissionGroups::PRODUCTS_PERMISSIONS, PermissionGroups::STORE_PERMISSIONS,
                    PermissionGroups::VOUCHERS_PERMISSIONS, PermissionGroups::PARTNERS_PERMISSIONS,
                    PermissionGroups::COUPONS_PERMISSIONS, PermissionGroups::USEFUL_LINKS_PERMISSIONS))
                    <x-sidebar.section title="Management" name="management" mobile="true" :collapsible="false">

                        @canany(PermissionGroups::USERS_PERMISSIONS)
                            <x-sidebar.item label="Users" icon="fas fa-users" route="admin.users.index" :route-pattern="'admin.users.*'"
                                mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::SERVERS_PERMISSIONS)
                            <x-sidebar.item label="Servers" icon="fas fa-server" route="admin.servers.index" :route-pattern="'admin.servers.*'"
                                mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::PRODUCTS_PERMISSIONS)
                            <x-sidebar.item label="Products" icon="fas fa-sliders-h" route="admin.products.index"
                                :route-pattern="'admin.products.*'" mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::STORE_PERMISSIONS)
                            <x-sidebar.item label="Store" icon="fas fa-shopping-basket" route="admin.store.index"
                                :route-pattern="'admin.store.*'" mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::VOUCHERS_PERMISSIONS)
                            <x-sidebar.item label="Vouchers" icon="fas fa-money-check-alt" route="admin.vouchers.index"
                                :route-pattern="'admin.vouchers.*'" mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::PARTNERS_PERMISSIONS)
                            <x-sidebar.item label="Partners" icon="fas fa-handshake" route="admin.partners.index"
                                :route-pattern="'admin.partners.*'" mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::COUPONS_PERMISSIONS)
                            <x-sidebar.item label="Coupons" icon="fas fa-ticket-alt" route="admin.coupons.index"
                                :route-pattern="'admin.coupons.*'" mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::USEFUL_LINKS_PERMISSIONS)
                            <x-sidebar.item label="Useful Links" icon="fas fa-link" route="admin.usefullinks.index"
                                :route-pattern="'admin.usefullinks.*'" mobile="true" />
                        @endcanany
                    </x-sidebar.section>
                @endcanany

                <!-- Logs Section -->
                @canany(array_merge(PermissionGroups::PAYMENTS_PERMISSIONS, PermissionGroups::LOGS_PERMISSIONS))
                    <x-sidebar.section title="Logs" name="logs" mobile="true" :collapsible="false">

                        @canany(PermissionGroups::PAYMENTS_PERMISSIONS)
                            <x-sidebar.item label="Payments" icon="fas fa-money-bill-wave" route="admin.payments.index"
                                :route-pattern="'admin.payments.*'" mobile="true" />
                        @endcanany

                        @canany(PermissionGroups::LOGS_PERMISSIONS)
                            <x-sidebar.item label="Activity Logs" icon="fas fa-clipboard-list"
                                route="admin.activitylogs.index" :route-pattern="'admin.activitylogs.*'" mobile="true" />
                        @endcanany
                    </x-sidebar.section>
                @endcanany
            </ul>
        </nav>
    </div>

    <x-sidebar.user-footer mobile="true" />
</aside>

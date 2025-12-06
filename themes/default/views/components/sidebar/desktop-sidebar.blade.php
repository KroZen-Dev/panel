@use(App\Constants\PermissionGroups)
<!-- Main Sidebar Container -->
<!-- Main Sidebar Container -->
<aside
    class="fixed left-0 top-0 bg-gray-50 dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 flex flex-col h-screen transition-all duration-200 overflow-x-hidden z-40 md:block hidden"
    x-data="{ open: localStorage.getItem('sidebarOpen') !== 'false', sections: { management: (localStorage.getItem('sidebarManagementOpen') !== 'false'), logs: (localStorage.getItem('sidebarLogsOpen') !== 'false') } }" x-init="$watch('sections.management', value => localStorage.setItem('sidebarManagementOpen', value));
    $watch('sections.logs', value => localStorage.setItem('sidebarLogsOpen', value));" @sidebar-toggle.window="open = $event.detail.open"
    :class="open ? 'w-64' : 'w-16'" x-cloak> <!-- Sidebar Content -->
    <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-900 pb-20">

        <!-- Brand / Logo (moved from navbar) -->
        <div class="flex items-center" :class="open ? 'px-3 py-4' : 'px-2 py-4'">
            <a href="{{ route('home') }}" class="flex items-center w-full group"
                :class="open ? 'justify-start' : 'justify-center'">
                <img width="32" height="32"
                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->exists('icon.png') ? asset('storage/icon.png') : asset('images/ctrlpanel_logo.png') }}"
                    alt="{{ config('app.name', 'Laravel') }} Logo"
                    class="rounded-full ring-2 ring-gray-800 dark:ring-gray-700 group-hover:ring-accent-500 transition-all duration-200">
                <span class="text-lg font-bold text-gray-900 dark:text-white transition-all duration-300"
                    :class="open ? 'ml-3 inline-block opacity-100' : 'hidden'">{{ config('app.name', 'CtrlPanel.gg') }}</span>
            </a>
        </div>

        <!-- Sidebar Menu -->
        <nav class="py-4" :class="open ? 'px-3' : 'px-2'">
            <ul class="space-y-1">
                <!-- Dashboard -->
                <x-sidebar.item label="Dashboard" icon="fas fa-home" :route="'home'" :route-pattern="'home'" />

                <!-- Servers -->
                <x-sidebar.item label="Servers" icon="fas fa-server" route="servers.index"
                    route-pattern="servers.*" :badge="Auth::user()->servers()->count() . '/' . Auth::user()->server_limit" />


                <!-- Store -->
                @if (config('app.env') == 'local' || $general_settings->store_enabled)
                    <x-sidebar.item label="Store" icon="fas fa-coins" route="store.index" :route-pattern="['store.*', 'checkout']" />
                @endif

                <!-- Support Ticket -->
                @php($ticket_enabled = app(App\Settings\TicketSettings::class)->enabled)
                @if ($ticket_enabled)
                    @canany(PermissionGroups::TICKET_PERMISSIONS)
                        <x-sidebar.item label="Support Ticket" icon="fas fa-plus-circle" route="ticket.index"
                            :route-pattern="'ticket.*'" />
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
                    <x-sidebar.section title="Administration" name="administration" :collapsible="false">
                        @canany(PermissionGroups::OVERVIEW_PERMISSIONS)
                            <x-sidebar.item label="Overview" icon="fas fa-home" route="admin.overview.index"
                                route-pattern="admin.overview.*" />
                        @endcanany
                        @canany(PermissionGroups::TICKET_ADMIN_PERMISSIONS)
                            <x-sidebar.item label="Ticket List" icon="fas fa-ticket-alt" route="admin.ticket.index"
                                :route-pattern="'admin.ticket.*'" />
                        @endcanany
                        @canany(PermissionGroups::TICKET_BLACKLIST_PERMISSIONS)
                            <x-sidebar.item label="Ticket Blacklist" icon="fas fa-user-times"
                                route="admin.ticket.blacklist" :route-pattern="'admin.ticket.blacklist'" />
                        @endcanany
                        @canany(PermissionGroups::ROLES_PERMISSIONS)
                            <x-sidebar.item label="Role Management" icon="fas fa-user-check" route="admin.roles.index"
                                :route-pattern="'admin.roles.*'" />
                        @endcanany
                        @canany(PermissionGroups::SETTINGS_PERMISSIONS)
                            <x-sidebar.item label="Settings" icon="fas fa-cogs" route="admin.settings.index"
                                :route-pattern="'admin.settings.*'" />
                        @endcanany
                        @canany(PermissionGroups::API_PERMISSIONS)
                            <x-sidebar.item label="Application API" icon="fas fa-gamepad" route="admin.api.index"
                                :route-pattern="'admin.api.*'" />
                        @endcanany
                    </x-sidebar.section>
                @endcanany

                @canany(array_merge(PermissionGroups::USERS_PERMISSIONS, PermissionGroups::SERVERS_PERMISSIONS,
                    PermissionGroups::PRODUCTS_PERMISSIONS, PermissionGroups::STORE_PERMISSIONS,
                    PermissionGroups::VOUCHERS_PERMISSIONS, PermissionGroups::PARTNERS_PERMISSIONS,
                    PermissionGroups::COUPONS_PERMISSIONS, PermissionGroups::USEFUL_LINKS_PERMISSIONS))
                    <x-sidebar.section title="Management" name="management">

                        @canany(PermissionGroups::USERS_PERMISSIONS)
                            <x-sidebar.item label="Users" icon="fas fa-users" route="admin.users.index"
                                :route-pattern="'admin.users.*'" />
                        @endcanany

                        @canany(PermissionGroups::SERVERS_PERMISSIONS)
                            <x-sidebar.item label="Servers" icon="fas fa-server" route="admin.servers.index"
                                :route-pattern="'admin.servers.*'" />
                        @endcanany

                        @canany(PermissionGroups::PRODUCTS_PERMISSIONS)
                            <x-sidebar.item label="Products" icon="fas fa-sliders-h" route="admin.products.index"
                                :route-pattern="'admin.products.*'" />
                        @endcanany

                        @canany(PermissionGroups::STORE_PERMISSIONS)
                            <x-sidebar.item label="Store" icon="fas fa-shopping-basket" route="admin.store.index"
                                :route-pattern="'admin.store.*'" />
                        @endcanany

                        @canany(PermissionGroups::VOUCHERS_PERMISSIONS)
                            <x-sidebar.item label="Vouchers" icon="fas fa-money-check-alt" route="admin.vouchers.index"
                                :route-pattern="'admin.vouchers.*'" />
                        @endcanany

                        @canany(PermissionGroups::PARTNERS_PERMISSIONS)
                            <x-sidebar.item label="Partners" icon="fas fa-handshake" route="admin.partners.index"
                                :route-pattern="'admin.partners.*'" />
                        @endcanany

                        @canany(PermissionGroups::COUPONS_PERMISSIONS)
                            <x-sidebar.item label="Coupons" icon="fas fa-ticket-alt" route="admin.coupons.index"
                                :route-pattern="'admin.coupons.*'" />
                        @endcanany

                        @canany(PermissionGroups::USEFUL_LINKS_PERMISSIONS)
                            <x-sidebar.item label="Useful Links" icon="fas fa-link" route="admin.usefullinks.index"
                                :route-pattern="'admin.usefullinks.*'" />
                        @endcanany
                    </x-sidebar.section>
                @endcanany

                @canany(array_merge(PermissionGroups::PAYMENTS_PERMISSIONS, PermissionGroups::LOGS_PERMISSIONS))
                    <x-sidebar.section title="Logs" name="logs">

                        @canany(PermissionGroups::PAYMENTS_PERMISSIONS)
                            <x-sidebar.item label="Payments" icon="fas fa-money-bill-wave" route="admin.payments.index"
                                :route-pattern="'admin.payments.*'" />
                        @endcanany

                        @canany(PermissionGroups::LOGS_PERMISSIONS)
                            <x-sidebar.item label="Activity Logs" icon="fas fa-clipboard-list"
                                route="admin.activitylogs.index" :route-pattern="'admin.activitylogs.*'" />
                        @endcanany
                    </x-sidebar.section>
                @endcanany
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>

    <x-sidebar.user-footer />
</aside>

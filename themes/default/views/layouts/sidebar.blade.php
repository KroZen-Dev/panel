@use(App\Constants\PermissionGroups)
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-open sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img width="64" height="64"
            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->exists('icon.png') ? asset('storage/icon.png') : asset('images/ctrlpanel_logo.png') }}"
            alt="{{ config('app.name', 'Laravel') }} Logo" class="brand-image img-circle" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'CtrlPanel.gg') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="overflow-y: auto">

        <!-- Sidebar Menu -->
        <nav class="my-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link @if (Request::routeIs('home')) active @endif">
                        <i class="nav-icon fa fa-home"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('servers.index') }}"
                        class="nav-link @if (Request::routeIs('servers.*')) active @endif">
                        <i class="nav-icon fa fa-server"></i>
                        <p>{{ __('Servers') }}
                            <span class="badge badge-info right">{{ Auth::user()->servers()->count() }} /
                                {{ Auth::user()->server_limit }}</span>
                        </p>
                    </a>
                </li>

                @if (config('app.env') == 'local' || $general_settings->store_enabled)
                    <li class="nav-item">
                        <a href="{{ route('store.index') }}"
                            class="nav-link @if (Request::routeIs('store.*') || Request::routeIs('checkout')) active @endif">
                            <i class="nav-icon fa fa-coins"></i>
                            <p>{{ __('Store') }}</p>
                        </a>
                    </li>
                @endif
                @php($ticket_enabled = app(App\Settings\TicketSettings::class)->enabled)
                @if ($ticket_enabled)
                    @canany(PermissionGroups::TICKET_PERMISSIONS)
                        <li class="nav-item">
                            <a href="{{ route('ticket.index') }}"
                                class="nav-link @if (Request::routeIs('ticket.*')) active @endif">
                                <i class="nav-icon fas fa-ticket-alt"></i>
                                <p>{{ __('Support Ticket') }}</p>
                            </a>
                        </li>
                    @endcanany
                @endif

                @canany(array_merge(PermissionGroups::TICKET_PERMISSIONS, PermissionGroups::OVERVIEW_PERMISSIONS,
                    PermissionGroups::TICKET_ADMIN_PERMISSIONS, PermissionGroups::TICKET_BLACKLIST_PERMISSIONS,
                    PermissionGroups::ROLES_PERMISSIONS, PermissionGroups::SETTINGS_PERMISSIONS,
                    PermissionGroups::API_PERMISSIONS, PermissionGroups::USERS_PERMISSIONS,
                    PermissionGroups::SERVERS_PERMISSIONS, PermissionGroups::PRODUCTS_PERMISSIONS,
                    PermissionGroups::STORE_PERMISSIONS, PermissionGroups::VOUCHERS_PERMISSIONS,
                    PermissionGroups::PARTNERS_PERMISSIONS, PermissionGroups::COUPONS_PERMISSIONS,
                    PermissionGroups::USEFUL_LINKS_PERMISSIONS, PermissionGroups::PAYMENTS_PERMISSIONS,
                    PermissionGroups::LOGS_PERMISSIONS))
                    <li class="nav-header">{{ __('Administration') }}</li>
                @endcanany

                @canany(PermissionGroups::OVERVIEW_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.overview.index') }}"
                            class="nav-link @if (Request::routeIs('admin.overview.*')) active @endif">
                            <i class="nav-icon fa fa-home"></i>
                            <p>{{ __('Overview') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::TICKET_ADMIN_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.ticket.index') }}"
                            class="nav-link @if (Request::routeIs('admin.ticket.index')) active @endif">
                            <i class="nav-icon fas fa-ticket-alt"></i>
                            <p>{{ __('Ticket List') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::TICKET_BLACKLIST_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.ticket.blacklist') }}"
                            class="nav-link @if (Request::routeIs('admin.ticket.blacklist')) active @endif">
                            <i class="nav-icon fas fa-user-times"></i>
                            <p>{{ __('Ticket Blacklist') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::ROLES_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}"
                            class="nav-link @if (Request::routeIs('admin.roles.*')) active @endif">
                            <i class="nav-icon fa fa-user-check"></i>
                            <p>{{ __('Role Management') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::SETTINGS_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') . '#icons' }}"
                            class="nav-link @if (Request::routeIs('admin.settings.*')) active @endif">
                            <i class="nav-icon fas fa-tools"></i>
                            <p>{{ __('Settings') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::API_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.api.index') }}"
                            class="nav-link @if (Request::routeIs('admin.api.*')) active @endif">
                            <i class="nav-icon fa fa-gamepad"></i>
                            <p>{{ __('Application API') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(array_merge(PermissionGroups::USERS_PERMISSIONS, PermissionGroups::SERVERS_PERMISSIONS,
                    PermissionGroups::PRODUCTS_PERMISSIONS))
                    <li class="nav-header">{{ __('Management') }}</li>
                @endcanany

                @canany(PermissionGroups::USERS_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link @if (Request::routeIs('admin.users.*')) active @endif">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('Users') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::SERVERS_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.servers.index') }}"
                            class="nav-link @if (Request::routeIs('admin.servers.*')) active @endif">
                            <i class="nav-icon fas fa-server"></i>
                            <p>{{ __('Servers') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::PRODUCTS_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}"
                            class="nav-link @if (Request::routeIs('admin.products.*')) active @endif">
                            <i class="nav-icon fas fa-sliders-h"></i>
                            <p>{{ __('Products') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::STORE_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.store.index') }}"
                            class="nav-link @if (Request::routeIs('admin.store.*')) active @endif">
                            <i class="nav-icon fas fa-shopping-basket"></i>
                            <p>{{ __('Store') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::VOUCHERS_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.vouchers.index') }}"
                            class="nav-link @if (Request::routeIs('admin.vouchers.*')) active @endif">
                            <i class="nav-icon fas fa-money-check-alt"></i>
                            <p>{{ __('Vouchers') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::PARTNERS_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.partners.index') }}"
                            class="nav-link @if (Request::routeIs('admin.partners.*')) active @endif">
                            <i class="nav-icon fas fa-handshake"></i>
                            <p>{{ __('Partners') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::COUPONS_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.coupons.index') }}"
                            class="nav-link @if (Request::routeIs('admin.coupons.*')) active @endif">
                            <i class="nav-icon fas fa-ticket-alt"></i>
                            <p>{{ __('Coupons') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::USEFUL_LINKS_PERMISSIONS)
                    <li class="nav-header">{{ __('Other') }}</li>
                @endcanany

                @canany(PermissionGroups::USEFUL_LINKS_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.usefullinks.index') }}"
                            class="nav-link @if (Request::routeIs('admin.usefullinks.*')) active @endif">
                            <i class="nav-icon fas fa-link"></i>
                            <p>{{ __('Useful Links') }}</p>
                        </a>
                    </li>
                @endcanany


                @canany(array_merge(PermissionGroups::PAYMENTS_PERMISSIONS, PermissionGroups::LOGS_PERMISSIONS))
                    <li class="nav-header">{{ __('Logs') }}</li>
                @endcanany

                @canany(PermissionGroups::PAYMENTS_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.payments.index') }}"
                            class="nav-link @if (Request::routeIs('admin.payments.*')) active @endif">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>{{ __('Payments') }}
                                <span class="badge badge-success right">{{ \App\Models\Payment::count() }}</span>
                            </p>
                        </a>
                    </li>
                @endcanany

                @canany(PermissionGroups::LOGS_PERMISSIONS)
                    <li class="nav-item">
                        <a href="{{ route('admin.activitylogs.index') }}"
                            class="nav-link @if (Request::routeIs('admin.activitylogs.*')) active @endif">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>{{ __('Activity Logs') }}</p>
                        </a>
                    </li>
                @endcanany
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

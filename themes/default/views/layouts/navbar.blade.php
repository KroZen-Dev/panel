    <!-- Navbar -->
    <nav class="main-header sticky-top navbar navbar-expand navbar-dark navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('home') }}" class="nav-link"><i class="mr-2 fas fa-home"></i>{{ __('Home') }}</a>
            </li>

            @foreach ($useful_links as $link)
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ $link->link }}" class="nav-link" target="__blank"><i class="{{ $link->icon }}"></i>
                        {{ $link->title }}</a>
                </li>
            @endforeach
        </ul>

        <!-- Right navbar links -->
        <ul class="ml-auto navbar-nav">

            <li class="nav-item dropdown">
                <a class="px-2 nav-link" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="mr-1 text-gray-600 d-lg-inline">
                        <small><i
                                class="mr-2 fas fa-coins"></i></small>{{ Currency::formatForDisplay(Auth::user()->credits) }}
                    </span>
                </a>
                <div class="shadow dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('store.index') }}">
                        <i class="mr-2 text-gray-400 fas fa-coins fa-sm fa-fw"></i>
                        {{ __('Store') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" data-toggle="modal" data-target="#redeemVoucherModal"
                        href="javascript:void(0)">
                        <i class="mr-2 text-gray-400 fas fa-money-check-alt fa-sm fa-fw"></i>
                        {{ __('Redeem code') }}
                    </a>
                </div>
            </li>

            <li class="nav-item dropdown no-arrow">
                <a class="px-2 nav-link dropdown-toggle no-arrow" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-1 text-gray-600 d-lg-inline small">
                        {{ Auth::user()->name }}
                        <img width="28px" height="28px" class="ml-1 rounded-circle position-relative"
                            src="{{ Auth::user()->getAvatar() }}">
                        @if (Auth::user()->unreadNotifications->count() != 0)
                            <span class="badge badge-warning navbar-badge position-absolute" style="top: 0px;">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="shadow dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                        <i class="mr-2 text-gray-400 fas fa-user fa-sm fa-fw"></i>
                        {{ __('Profile') }}
                    </a>
                    <a class="dropdown-item position-relative" href="{{ route('notifications.index') }}">
                        <i class="mr-2 text-gray-400 fas fa-bell fa-sm fa-fw"></i>
                        {{ __('Notifications') }}
                        @if (Auth::user()->unreadNotifications->count() != 0)
                            <span class="badge badge-warning navbar-badge position-absolute" style="top: 10px;">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>
                    <a class="dropdown-item" href="{{ route('preferences.index') }}">
                        <i class="mr-2 text-gray-400 fas fa-cog fa-sm fa-fw"></i>
                        {{ __('Preferences') }}
                    </a>
                    {{-- <a class="dropdown-item" href="#"> --}}
                    {{-- <i class="mr-2 text-gray-400 fas fa-list fa-sm fa-fw"></i> --}}
                    {{-- Activity Log --}}
                    {{-- </a> --}}
                    @if (session()->get('previousUser'))
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('users.logbackin') }}">
                            <i class="mr-2 text-gray-400 fas fa-sign-in-alt fa-sm fa-fw"></i>
                            {{ __('Log back in') }}
                        </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="mr-2 text-gray-400 fas fa-sign-out-alt fa-sm fa-fw"></i>
                            {{ __('Logout') }}
                        </button>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

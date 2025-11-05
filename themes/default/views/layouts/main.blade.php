<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php($website_settings = app(App\Settings\WebsiteSettings::class))
    @php($general_settings = app(App\Settings\GeneralSettings::class))
    @php($discord_settings = app(App\Settings\DiscordSettings::class))
    @use('App\Constants\PermissionGroups')

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="{{ $website_settings->seo_title }}" property="og:title">
    <meta content="{{ $website_settings->seo_description }}" property="og:description">
    <meta
        content='{{ \Illuminate\Support\Facades\Storage::disk('public')->exists('logo.png') ? asset('storage/logo.png') : asset('images/ctrlpanel_logo.png') }}'
        property="og:image">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon"
        href="{{ \Illuminate\Support\Facades\Storage::disk('public')->exists('favicon.ico') ? asset('storage/favicon.ico') : asset('favicon.ico') }}"
        type="image/x-icon">

    <script src="{{ asset('plugins/alpinejs/3.12.0_cdn.min.js') }}" defer></script>

    {{-- <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">

    {{-- summernote --}}
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">

    {{-- datetimepicker --}}
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">

    <link rel="preload" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    </noscript>
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- tinymce -->
    <script src="{{ asset('plugins/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <style>
        #userDropdown.dropdown-toggle::after {
            display: none !important;
        }
    </style>
    @vite('themes/default/sass/app.scss')
</head>

<body class="sidebar-mini layout-fixed dark-mode" style="height: auto;">
    <div class="wrapper">
        @include('layouts.navbar')
        @include('layouts.sidebar')
        <div class="content-wrapper">
            @yield('content')
            @include('modals.redeem_voucher_modal')
        </div>
        @include('layouts.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- select2 -->
    <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>

    <!-- Moment.js -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>

    <!-- Datetimepicker -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Select2 -->
    <script src={{ asset('plugins/select2/js/select2.min.js') }}></script>


    <script>
        $(document).ready(function() {
            $('[data-toggle="popover"]').popover();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        $(document).on('alpine:init', () => {
            Alpine.magic('currency', () => {
                return {
                    format: (amount) => {
                        return (amount / 1000);
                    },
                }
            });
        })
    </script>
    <script>
        @if (Session::has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '{{ Session::get('error') }}',
            })
        @endif
        @if (Session::has('success'))
            Swal.fire({
                icon: 'success',
                title: '{{ Session::get('success') }}',
                position: 'top-end',
                showConfirmButton: false,
                background: '#343a40',
                toast: true,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
        @endif
        @if (Session::has('info'))
            Swal.fire({
                icon: 'info',
                title: '{{ Session::get('info') }}',
                position: 'top-end',
                showConfirmButton: false,
                background: '#343a40',
                toast: true,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
        @endif
        @if (Session::has('warning'))
            Swal.fire({
                icon: 'warning',
                title: '{{ Session::get('warning') }}',
                position: 'top-end',
                showConfirmButton: false,
                background: '#343a40',
                toast: true,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
        @endif
    </script>
</body>

</html>

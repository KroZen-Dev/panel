@extends('layouts.main')

@section('content')
    <!-- CONTENT HEADER -->
    <div class="px-6 pt-6 pb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ __('Servers') }}</h1>
                <nav class="flex gap-2 mt-2 text-sm text-gray-600 dark:text-gray-400">
                    <a href="{{ route('home') }}" class="hover:text-accent-500 transition-colors">{{ __('Dashboard') }}</a>
                    <span>/</span>
                    <span class="text-gray-900 dark:text-white font-semibold">{{ __('Servers') }}</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <section class="content px-6 pb-6">
        <!-- ACTION BUTTONS -->
        <div class="flex flex-col md:flex-row gap-3 mb-6">
            <a
                @if (Auth::user()->Servers->count() >= Auth::user()->server_limit) onclick="return false" class="opacity-50 cursor-not-allowed" title="Server limit reached!" @else href="{{ route('servers.create') }}" @endcan
               @cannot('user.server.create') onclick="return false" class="opacity-50 cursor-not-allowed" title="No Permission!" @endcannot
                class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-accent-600 to-accent-500 hover:from-accent-500 hover:to-accent-600 text-white font-semibold rounded-lg transition-all duration-200 @if (Auth::user()->Servers->count() >= Auth::user()->server_limit || !Auth::user()->can('user.server.create')) opacity-50 cursor-not-allowed @endif">
                <i class="fa fa-plus"></i>
                {{ __('Create Server') }}
            </a>
            @if (Auth::user()->Servers->count() > 0 && !empty($phpmyadmin_url))
                <a href="{{ $phpmyadmin_url }}" target="_blank"
                    class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-600 text-white font-semibold rounded-lg transition-all duration-200">
                    <i class="fas fa-database"></i>
                    {{ __('Database') }}
                </a>
            @endif
        </div>

        <!-- SERVERS GRID -->
        @if (count($servers) === 0)
            <div
                class="flex items-center justify-center py-12 px-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700/50">
                <div class="text-center">
                    <p class="text-gray-600 dark:text-gray-400 text-lg">{{ __('No servers found') }}</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($servers as $server)
                    @if ($server->location && $server->node && $server->nest && $server->egg)
                        <div
                            class="bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700/50 flex flex-col">
                            <!-- Header -->
                            <div class="px-6 pt-6 pb-4 border-b border-gray-200 dark:border-gray-700/50">
                                <h5 class="text-lg font-bold text-gray-900 dark:text-white">{{ $server->name }}</h5>
                            </div>

                            <!-- Body -->
                            <div class="px-6 py-4 flex-1 space-y-4">
                                <!-- Status -->
                                <div class="flex justify-between items-start gap-2">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('Status') }}:</span>
                                    <div>
                                        @if ($server->suspended)
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-400">{{ __('Suspended') }}</span>
                                        @elseif($server->canceled)
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-yellow-500/20 text-yellow-400">{{ __('Canceled') }}</span>
                                        @else
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400">{{ __('Active') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="flex justify-between items-center gap-2">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('Location') }}:</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-900 dark:text-gray-100">{{ $server->location }}</span>
                                        <i data-tippy-content="{{ __('Node') }}: {{ $server->node }}"
                                            data-tippy-interactive="true"
                                            class="fas fa-info-circle text-gray-500 dark:text-gray-400 cursor-help"></i>
                                    </div>
                                </div>

                                <!-- Software -->
                                <div class="flex justify-between items-start gap-2">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('Software') }}:</span>
                                    <span
                                        class="text-gray-900 dark:text-gray-100 text-right break-words">{{ $server->nest }}</span>
                                </div>

                                <!-- Specification -->
                                <div class="flex justify-between items-start gap-2">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('Specification') }}:</span>
                                    <span
                                        class="text-gray-900 dark:text-gray-100 text-right break-words">{{ $server->egg }}</span>
                                </div>

                                <!-- Resource Plan -->
                                <div class="flex justify-between items-start gap-2">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('Resource plan') }}:</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-900 dark:text-gray-100">{{ $server->product->name }}</span>
                                        <i data-tippy-content="{{ __('CPU') }}: {{ $server->product->cpu / 100 }} {{ __('vCores') }} <br/>{{ __('RAM') }}: {{ $server->product->memory }} MB <br/>{{ __('Disk') }}: {{ $server->product->disk }} MB <br/>{{ __('Backups') }}: {{ $server->product->backups }} <br/> {{ __('MySQL Databases') }}: {{ $server->product->databases }} <br/> {{ __('Allocations') }}: {{ $server->product->allocations }} <br/>{{ __('OOM Killer') }}: {{ $server->product->oom_killer ? __('enabled') : __('disabled') }} <br/> {{ __('Billing Period') }}: {{ $server->product->billing_period }}"
                                            data-tippy-interactive="true"
                                            class="fas fa-info-circle text-gray-500 dark:text-gray-400 cursor-help"></i>
                                    </div>
                                </div>

                                <!-- Next Billing Cycle -->
                                <div class="flex justify-between items-start gap-2">
                                    <span class="text-gray-600 dark:text-gray-400 break-words"
                                        style="hyphens: auto">{{ __('Next Billing Cycle') }}:</span>
                                    <div class="text-gray-900 dark:text-gray-100 text-right">
                                        @if ($server->suspended)
                                            <span>-</span>
                                        @else
                                            @switch($server->product->billing_period)
                                                @case('monthly')
                                                    {{ \Carbon\Carbon::parse($server->last_billed)->addMonth()->toDayDateTimeString() }}
                                                @break

                                                @case('weekly')
                                                    {{ \Carbon\Carbon::parse($server->last_billed)->addWeek()->toDayDateTimeString() }}
                                                @break

                                                @case('daily')
                                                    {{ \Carbon\Carbon::parse($server->last_billed)->addDay()->toDayDateTimeString() }}
                                                @break

                                                @case('hourly')
                                                    {{ \Carbon\Carbon::parse($server->last_billed)->addHour()->toDayDateTimeString() }}
                                                @break

                                                @case('quarterly')
                                                    {{ \Carbon\Carbon::parse($server->last_billed)->addMonths(3)->toDayDateTimeString() }}
                                                @break

                                                @case('half-annually')
                                                    {{ \Carbon\Carbon::parse($server->last_billed)->addMonths(6)->toDayDateTimeString() }}
                                                @break

                                                @case('annually')
                                                    {{ \Carbon\Carbon::parse($server->last_billed)->addYear()->toDayDateTimeString() }}
                                                @break

                                                @default
                                                    {{ __('Unknown') }}
                                            @endswitch
                                        @endif
                                    </div>
                                </div>

                                <!-- Price -->
                                <div
                                    class="flex justify-between items-start gap-2 pt-2 border-t border-gray-200 dark:border-gray-700/50">
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">{{ __('Price') }}:</span>
                                        <span class="text-gray-500 dark:text-gray-500 text-sm">
                                            ({{ $credits_display_name }})
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-gray-500 dark:text-gray-500 text-sm">
                                            @if ($server->product->billing_period == 'monthly')
                                                {{ __('per Month') }}
                                            @elseif($server->product->billing_period == 'half-annually')
                                                {{ __('per 6 Months') }}
                                            @elseif($server->product->billing_period == 'quarterly')
                                                {{ __('per 3 Months') }}
                                            @elseif($server->product->billing_period == 'annually')
                                                {{ __('per Year') }}
                                            @elseif($server->product->billing_period == 'weekly')
                                                {{ __('per Week') }}
                                            @elseif($server->product->billing_period == 'daily')
                                                {{ __('per Day') }}
                                            @elseif($server->product->billing_period == 'hourly')
                                                {{ __('per Hour') }}
                                            @endif
                                            <i data-tippy-content="{{ __('Your') . ' ' . $credits_display_name . ' ' . __('are reduced') . ' ' . $server->product->billing_period . '. ' . __('This however calculates to ') . Currency::formatForDisplay($server->product->getMonthlyPrice()) . ' ' . $credits_display_name . ' ' . __('per Month') }}"
                                                data-tippy-interactive="true"
                                                class="fas fa-info-circle text-gray-500 dark:text-gray-400 cursor-help"></i>
                                        </div>
                                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $server->product->display_price }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div
                                class="flex gap-2 px-6 py-4 border-t border-gray-200 dark:border-gray-700/50 bg-gray-50 dark:bg-gray-900/50">
                                <a href="{{ $pterodactyl_url }}/server/{{ $server->identifier }}" target="_blank"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors text-sm"
                                    title="{{ __('Manage Server') }}">
                                    <i class="fas fa-tools"></i>
                                </a>
                                <a href="{{ route('servers.show', ['server' => $server->id]) }}"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors text-sm"
                                    title="{{ __('Server Settings') }}">
                                    <i class="fas fa-cog"></i>
                                </a>
                                <button onclick="handleServerCancel('{{ $server->id }}');"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg transition-colors text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                    {{ $server->suspended || $server->canceled ? 'disabled' : '' }}
                                    title="{{ __('Cancel Server') }}">
                                    <i class="fas fa-ban"></i>
                                </button>
                                <button onclick="handleServerDelete('{{ $server->id }}');"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors text-sm"
                                    title="{{ __('Delete Server') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
        <!-- END CUSTOM CONTENT -->
    </section>
    <!-- END CONTENT -->

    <script>
        const handleServerCancel = (serverId) => {
            // Handle server cancel with sweetalert
            Swal.fire({
                title: "{{ __('Cancel Server?') }}",
                text: "{{ __('This will cancel your current server to the next billing period. It will get suspended when the current period runs out.') }}",
                icon: 'warning',
                confirmButtonColor: '#d9534f',
                showCancelButton: true,
                confirmButtonText: "{{ __('Yes, cancel it!') }}",
                cancelButtonText: "{{ __('No, abort!') }}",
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    // Delete server
                    fetch("{{ route('servers.cancel', '') }}" + '/' + serverId, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => {
                        window.location.reload();
                    }).catch((error) => {
                        Swal.fire({
                            title: "{{ __('Error') }}",
                            text: "{{ __('Something went wrong, please try again later.') }}",
                            icon: 'error',
                            confirmButtonColor: '#d9534f',
                        })
                    })
                    return
                }
            })
        }

        const handleServerDelete = (serverId) => {
            Swal.fire({
                title: "{{ __('Delete Server?') }}",
                html: "{!! __(
                    'This is an irreversible action, all files of this server will be removed. <strong>No funds will get refunded</strong>. We recommend deleting the server when server is suspended.',
                ) !!}",
                icon: 'warning',
                confirmButtonColor: '#d9534f',
                showCancelButton: true,
                confirmButtonText: "{{ __('Yes, delete it!') }}",
                cancelButtonText: "{{ __('No, abort!') }}",
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    // Delete server
                    fetch("{{ route('servers.destroy', '') }}" + '/' + serverId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => {
                        window.location.reload();
                    }).catch((error) => {
                        Swal.fire({
                            title: "{{ __('Error') }}",
                            text: "{{ __('Something went wrong, please try again later.') }}",
                            icon: 'error',
                            confirmButtonColor: '#d9534f',
                        })
                    })
                    return
                }
            });

        }

        document.addEventListener('DOMContentLoaded', () => {
            // Tippy tooltips are initialized globally in app.js
        });

        $(function() {
            // Tooltips are now handled by Tippy.js
        })
    </script>
@endsection

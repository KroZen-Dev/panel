@section('content')
    <!-- CONTENT HEADER -->
    <div class="px-6 pt-6 pb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ __('Server Settings') }}</h1>
                <nav class="flex gap-2 mt-2 text-sm text-gray-600 dark:text-gray-400">
                    <a href="{{ route('home') }}" class="hover:text-accent-500 transition-colors">{{ __('Dashboard') }}</a>
                    <span>/</span>
                    <a href="{{ route('servers.index') }}"
                        class="hover:text-accent-500 transition-colors">{{ __('Servers') }}</a>
                    <span>/</span>
                    <a href="{{ route('servers.show', $server->id) }}"
                        class="hover:text-accent-500 transition-colors">{{ __('Settings') }}</a>
                </nav>
            </div>
        </div>
    </div>
    <!-- END CONTENT HEADER -->

    <!-- MAIN CONTENT -->
    <section class="px-6 pb-6" x-data="{
        showUpgradeModal: false,
        showDeleteModal: false,
        showBillingPriorityModal: false
    }">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- STAT CARDS -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                <!-- Server Name -->
                <div
                    class="bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                                {{ __('SERVER NAME') }}</p>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="domain_text">{{ $server->name }}
                            </h3>
                        </div>
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-full flex items-center justify-center shadow-lg">
                            <i class="bx bx-fingerprint text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- CPU -->
                <div
                    class="bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                                {{ __('CPU') }}</p>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                @if ($server->product->cpu == 0)
                                    {{ __('Unlimited') }}
                                @else
                                    {{ $server->product->cpu }} %
                                @endif
                            </h3>
                        </div>
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg">
                            <i class="bx bxs-chip text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Memory -->
                <div
                    class="bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                                {{ __('MEMORY') }}</p>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                @if ($server->product->memory == 0)
                                    {{ __('Unlimited') }}
                                @else
                                    {{ $server->product->memory }}MB
                                @endif
                            </h3>
                        </div>
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg">
                            <i class="bx bxs-memory-card text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Storage -->
                <div
                    class="bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                                {{ __('STORAGE') }}</p>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                @if ($server->product->disk == 0)
                                    {{ __('Unlimited') }}
                                @else
                                    {{ $server->product->disk }}MB
                                @endif
                            </h3>
                        </div>
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center shadow-lg">
                            <i class="bx bxs-hdd text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAIN SECTIONS -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Server Information -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700/50 flex items-center gap-2">
                        <i class="fas fa-sliders-h text-accent-500"></i>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Server Information') }}</h2>
                        <div class="ml-auto text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-calendar-alt mr-1" data-tippy-content="{{ __('Created at') }}"></i>
                            {{ $server->created_at->isoFormat('LL') }}
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="flex items-center justify-between py-2">
                                <span
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Server ID') }}</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white font-mono truncate max-w-48">{{ $server->id }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Pterodactyl ID') }}</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white font-mono truncate max-w-48">{{ $server->identifier }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Hourly Price') }}</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ Currency::formatForDisplay($server->product->getHourlyPrice()) }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Monthly Price') }}</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ Currency::formatForDisplay($server->product->getMonthlyPrice()) }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Location') }}</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white truncate max-w-48">{{ $serverAttributes['relationships']['location']['attributes']['short'] }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Node') }}</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white truncate max-w-48">{{ $serverAttributes['relationships']['node']['attributes']['name'] }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Backups') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $server->product->backups }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('OOM Killer') }}</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $server->product->oom_killer ? __('enabled') : __('disabled') }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('MySQL Database') }}</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $server->product->databases }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700/50">
                        <div class="flex justify-center gap-3">
                            @if ($server_enable_upgrade && Auth::user()->can('user.server.upgrade'))
                                <x-button variant="outline" @click="showUpgradeModal = true">
                                    <i class="fas fa-upload mr-2"></i>
                                    {{ __('Upgrade / Downgrade') }}
                                </x-button>
                            @endif
                            <x-button variant="danger" @click="showDeleteModal = true">
                                <i class="fas fa-trash mr-2"></i>
                                {{ __('Delete') }}
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Server Billing Priority -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700/50 flex items-center gap-2">
                    <i class="fas fa-flag text-accent-500"></i>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Server Billing Priority') }}</h2>
                </div>

                <div class="px-6 py-6">
                    <div class="flex items-center justify-between">
                        <span
                            class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Current Billing Priority') }}</span>
                        <span
                            class="text-sm text-gray-900 dark:text-white">{{ $server->effective_billing_priority->label() }}
                            - ({{ $server->effective_billing_priority->description() }})</span>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700/50">
                    <div class="flex justify-center">
                        <x-button variant="outline" @click="showBillingPriorityModal = true">
                            <i class="fas fa-flag mr-2"></i>
                            {{ __('Change Billing Priority') }}
                        </x-button>
                    </div>
                </div>
            </div>
        </div>

        @if ($server_enable_upgrade && Auth::user()->can('user.server.upgrade'))
            <!-- Upgrade Modal -->
            <div x-cloak x-show="showUpgradeModal" x-transition.opacity.duration-200
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
                @click="showUpgradeModal = false" @keydown.escape.window="showUpgradeModal = false">
                <div class="w-full max-w-md mx-auto" @click.stop>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700/50 flex items-center justify-between">
                            <h5 class="text-lg font-bold text-gray-900 dark:text-white">
                                {{ __('Upgrade/Downgrade Server') }}</h5>
                            <button type="button" class="text-gray-400 hover:text-gray-600"
                                @click="showUpgradeModal = false">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="px-6 py-4">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>{{ __('Current Product') }}:</strong> {{ $server->product->name }}
                            </p>
                            <form action="{{ route('servers.upgrade', ['server' => $server->id]) }}" method="POST"
                                class="mt-4 space-y-4 upgrade-form">
                                @csrf
                                <select
                                    x-on:change="$el.value ? $refs.upgradeSubmit.disabled = false : $refs.upgradeSubmit.disabled = true"
                                    name="product_upgrade" id="product_upgrade"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-accent-500">
                                    <option value="">{{ __('Select the product') }}</option>
                                    @foreach ($products as $product)
                                        @if ($product->id != $server->product->id && $product->disabled == false)
                                            <option value="{{ $product->id }}"
                                                @if ($product->doesNotFit) disabled @endif>
                                                {{ $product->name }} [ {{ $credits_display_name }}
                                                {{ $product->display_price }}
                                                @if ($product->doesNotFit)
                                                    ] {{ __('Server can\'t fit on this node') }}
                                                @else
                                                    @if ($product->minimum_credits != null)
                                                        / {{ __('Required') }}:
                                                        {{ $product->display_minimum_credits }}
                                                        {{ $credits_display_name }}
                                                    @endif ]
                                                @endif
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <p class="text-sm text-amber-600 dark:text-amber-400">
                                    <strong>{{ __('Caution') }}:</strong>
                                    {{ __('Upgrading/Downgrading your server will reset your billing cycle to now. Your overpayed Credits will be refunded. The price for the new billing cycle will be withdrawed') }}.
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Server will be automatically restarted once upgraded') }}
                                </p>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="flex justify-end border-t border-gray-200 dark:border-gray-700/50 pt-4">
                                    <x-button x-ref="upgradeSubmit" type="submit" variant="primary"
                                        class="upgrade-once" disabled>
                                        {{ __('Change Product') }}
                                    </x-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Delete Modal -->
        <div x-cloak x-show="showDeleteModal" x-transition.opacity.duration-200
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" @click="showDeleteModal = false"
            @keydown.escape.window="showDeleteModal = false">
            <div class="w-full max-w-md mx-auto" @click.stop>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700/50 flex items-center justify-between">
                        <h5 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Delete Server') }}</h5>
                        <button type="button" class="text-gray-400 hover:text-gray-600"
                            @click="showDeleteModal = false">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                        {{ __('This is an irreversible action, all files of this server will be removed!') }}
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700/50 flex justify-end gap-3">
                        <x-button variant="secondary" @click="showDeleteModal = false">
                            {{ __('Cancel') }}
                        </x-button>
                        <form class="inline" method="post"
                            action="{{ route('servers.destroy', ['server' => $server->id]) }}">
                            @csrf
                            @method('DELETE')
                            <x-button variant="danger" data-tippy-content="{{ __('This cannot be undone.') }}">
                                {{ __('Delete') }}
                            </x-button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Priority Modal -->
        <div x-cloak x-show="showBillingPriorityModal" x-transition.opacity.duration-200
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
            @click="showBillingPriorityModal = false" @keydown.escape.window="showBillingPriorityModal = false">
            <div class="w-full max-w-md mx-auto" @click.stop>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700/50 flex items-center justify-between">
                        <h5 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ __('Update Billing Priority') }}</h5>
                        <button type="button" class="text-gray-400 hover:text-gray-600"
                            @click="showBillingPriorityModal = false">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="px-6 py-4">
                        <form action="{{ route('servers.updateBillingPriority', ['server' => $server->id]) }}"
                            method="POST" id="billing_priority_form" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            <select
                                x-on:change="$el.value ? $refs.prioritySubmit.disabled = false : $refs.prioritySubmit.disabled = true"
                                name="billing_priority" id="billing_priority"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-accent-500">
                                <option value="">{{ __('Select the billing priority') }}</option>
                                @foreach (App\Enums\BillingPriority::cases() as $priority)
                                    <option value="{{ $priority->value }}" @selected($server->effective_billing_priority == $priority)>
                                        {{ $priority->label() }} - ({{ $priority->description() }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="flex justify-end">
                                <x-button x-ref="prioritySubmit" type="submit" variant="primary"
                                    class="billing_priority_submit" disabled>
                                    {{ __('Update') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.upgrade-form').forEach((form) => {
                form.addEventListener('submit', () => {
                    form.querySelectorAll('.upgrade-once').forEach((button) => {
                        button.setAttribute('disabled', 'disabled');
                    });
                });
            });

            const billingForm = document.getElementById('billing_priority_form');
            if (billingForm) {
                billingForm.addEventListener('submit', () => {
                    billingForm.querySelectorAll('.billing_priority_submit').forEach((button) => {
                        button.setAttribute('disabled', 'disabled');
                    });
                });
            }

            if (window.tippy) {
                tippy('[data-tippy-content]', {
                    allowHTML: true,
                    interactive: true,
                    animation: 'shift-away',
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                });
            }
        });
    </script>
@endsection

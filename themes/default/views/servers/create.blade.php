@extends('layouts.main')

@section('content')
    <div class="px-6 pt-6 pb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ __('Create Server') }}</h1>
                <nav class="flex gap-2 mt-2 text-sm text-gray-600 dark:text-gray-400">
                    <a href="{{ route('home') }}" class="hover:text-accent-500 transition-colors">{{ __('Dashboard') }}</a>
                    <span>/</span>
                    <a href="{{ route('servers.index') }}"
                        class="hover:text-accent-500 transition-colors">{{ __('Servers') }}</a>
                    <span>/</span>
                    <span class="text-gray-900 dark:text-white font-semibold">{{ __('Create') }}</span>
                </nav>
            </div>
        </div>
    </div>

    <section x-data="serverApp()" class="px-6 pb-10">
        <form action="{{ route('servers.store') }}" x-on:submit="submitClicked = true" method="post" id="serverForm"
            class="max-w-6xl mx-auto space-y-6">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="product" id="product" x-model="selectedProduct">
            <input type="hidden" name="egg_variables" id="egg_variables">

            @if (!$server_creation_enabled)
                <div
                    class="rounded-lg border border-yellow-200 bg-yellow-50 text-yellow-800 dark:border-yellow-500/40 dark:bg-yellow-900/40 dark:text-yellow-100 p-4">
                    <p class="font-semibold">{{ __('Server creation is currently disabled for regular users.') }}</p>
                    <p class="text-sm mt-1">{{ __('Admins can enable it in Settings > Server.') }}</p>
                </div>
            @endif

            @if ($productCount === 0 || $nodeCount === 0 || count($nests) === 0 || count($eggs) === 0)
                <div
                    class="rounded-lg border border-red-200 bg-red-50 text-red-800 dark:border-red-500/40 dark:bg-red-900/40 dark:text-red-100 p-4">
                    <div class="flex items-center gap-2 font-semibold">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ __('Setup required before creating servers') }}</span>
                    </div>
                    <ul class="mt-2 list-disc list-inside space-y-1 text-sm">
                        @if ($productCount === 0)
                            <li>{{ __('No products available.') }}</li>
                        @endif
                        @if ($nodeCount === 0)
                            <li>{{ __('No nodes have been linked.') }}</li>
                        @endif
                        @if (count($nests) === 0)
                            <li>{{ __('No nests available.') }}</li>
                        @endif
                        @if (count($eggs) === 0)
                            <li>{{ __('No eggs have been linked.') }}</li>
                        @endif
                    </ul>
                    @if (Auth::user()->hasRole('Admin'))
                        <p class="mt-3 text-sm">
                            {{ __('Link products to nodes and eggs, and ensure at least one valid product is available.') }}
                            <a class="text-accent-500 hover:text-accent-400 font-semibold"
                                href="{{ route('admin.overview.sync') }}">{{ __('Sync now') }}</a>
                        </p>
                    @endif
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="rounded-lg border border-red-200 bg-red-50 text-red-800 dark:border-red-500/40 dark:bg-red-900/40 dark:text-red-100 p-4">
                    <p class="font-semibold mb-2">{{ __('Please fix the following') }}:</p>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 rounded-xl shadow-sm p-6 relative overflow-hidden">
                        <div x-show="loading"
                            class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm flex items-center justify-center"
                            x-cloak>
                            <div
                                class="h-10 w-10 border-4 border-accent-500 border-t-transparent rounded-full animate-spin">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="name"
                                    class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('Name') }}</label>
                                <input x-model="name" id="name" name="name" type="text" required
                                    class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-2.5 focus:ring-2 focus:ring-accent-500 focus:border-accent-500"
                                    placeholder="{{ __('My new server') }}">
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nest"
                                        class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('Software / Games') }}</label>
                                    <select
                                        class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-2.5 focus:ring-2 focus:ring-accent-500 focus:border-accent-500"
                                        required name="nest" id="nest" x-model="selectedNest" @change="setEggs()">
                                        <option selected disabled hidden value="null">
                                            {{ count($nests) > 0 ? __('Please select software ...') : __('---') }}
                                        </option>
                                        @foreach ($nests as $nest)
                                            <option value="{{ $nest->id }}">{{ $nest->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="egg"
                                        class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('Specification') }}</label>
                                    <select id="egg" required name="egg"
                                        class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-2.5 focus:ring-2 focus:ring-accent-500 focus:border-accent-500"
                                        :disabled="eggs.length == 0" x-model="selectedEgg" @change="fetchLocations()">
                                        <option x-text="getEggInputText()" selected disabled hidden value="null"></option>
                                        <template x-for="egg in eggs" :key="egg.id">
                                            <option x-text="egg.name" :value="egg.id"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <label for="location"
                                            class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('Location') }}</label>
                                        @if ($location_description_enabled)
                                            <i x-show="locationDescription != null"
                                                x-bind:data-tippy-content="locationDescription"
                                                data-tippy-interactive="true" class="fas fa-info-circle text-gray-500"></i>
                                        @endif
                                    </div>
                                    <select name="location" required id="location" x-model="selectedLocation"
                                        :disabled="!fetchedLocations"
                                        class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-2.5 focus:ring-2 focus:ring-accent-500 focus:border-accent-500"
                                        @change="fetchProducts()">
                                        <option x-text="getLocationInputText()" disabled selected hidden value="null">
                                        </option>
                                        <template x-for="location in locations" :key="location.id">
                                            <option x-text="location.name" :value="location.id"></option>
                                        </template>
                                    </select>
                                </div>

                                <div>
                                    <div class="flex items-center gap-2">
                                        <label for="billing_priority"
                                            class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('Billing Priority (optional)') }}</label>
                                        <i data-tippy-content="{{ __('Defines the priority for server billing. If not provided, the product default will be used.') }}"
                                            data-tippy-interactive="true" class="fas fa-info-circle text-gray-500"></i>
                                    </div>
                                    <select id="billing_priority"
                                        class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-2.5 focus:ring-2 focus:ring-accent-500 focus:border-accent-500"
                                        name="billing_priority" autocomplete="off" x-model="selectedBillingPriority">
                                        <option value="" selected>{{ __('Use product default') }}</option>
                                        @foreach (App\Enums\BillingPriority::cases() as $priority)
                                            <option value="{{ $priority->value }}">{{ $priority->label() }} -
                                                {{ $priority->description() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <template
                                x-if="selectedProduct != null && selectedProduct != '' && locations.length == 0 && !loading">
                                <div
                                    class="rounded-lg border border-red-200 bg-red-50 text-red-800 dark:border-red-500/40 dark:bg-red-900/40 dark:text-red-100 p-3">
                                    {{ __('There seem to be no nodes available for this specification. Admins have been notified. Please try again later or contact us.') }}
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 rounded-xl shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Selection summary') }}
                            </h3>
                            <span
                                class="text-xs px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200"
                                x-text="isFormValid() ? '{{ __('Ready') }}' : '{{ __('Incomplete') }}'"></span>
                        </div>
                        <dl class="space-y-3 text-sm text-gray-700 dark:text-gray-200">
                            <div class="flex justify-between">
                                <dt class="font-medium">{{ __('Name') }}</dt>
                                <dd class="text-right" x-text="name || '{{ __('Not set') }}'"></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium">{{ __('Software / Games') }}</dt>
                                <dd class="text-right" x-text="selectedNestObject.name || '{{ __('Not selected') }}'">
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium">{{ __('Specification') }}</dt>
                                <dd class="text-right" x-text="selectedEggObject.name || '{{ __('Not selected') }}'">
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium">{{ __('Location') }}</dt>
                                <dd class="text-right"
                                    x-text="selectedLocationObject.name || '{{ __('Not selected') }}'"></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium">{{ __('Billing Priority') }}</dt>
                                <dd class="text-right"
                                    x-text="selectedBillingPriority ? billingPriorityLabels[selectedBillingPriority] : (selectedProductObject.default_billing_priority_label || '{{ __('Product default') }}')">
                                </dd>
                            </div>
                        </dl>
                        <div
                            class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/60 px-3 py-2 text-xs text-gray-600 dark:text-gray-300">
                            {{ __('Complete the selections on the left to see available plans. Prices and limits depend on your chosen location.') }}
                        </div>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 rounded-xl shadow-sm p-6 text-sm text-gray-700 dark:text-gray-300">
                        <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-2">{{ __('Tips') }}</h4>
                        <ul class="space-y-1 list-disc list-inside">
                            <li>{{ __('Pick the software first, then the spec, then the location.') }}</li>
                            <li>{{ __('Billing priority defaults to the product value if you leave it unchanged.') }}</li>
                            <li>{{ __('If a plan requires variables, you will be prompted before creation.') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="space-y-3" x-show="selectedLocation != null" x-cloak>
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Available plans') }}</h3>
                    <span class="text-sm text-gray-600 dark:text-gray-400" x-text="locationDescription || ''"></span>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="product in products" :key="product.id">
                        <div
                            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-4 flex flex-col gap-3">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="billingPeriodTranslations[product.billing_period]"></p>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="product.name">
                                    </h4>
                                </div>
                                <span
                                    class="text-xs px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200"
                                    x-text="product.serverlimit > 0 ? product.servers_count + ' / ' + product.serverlimit : '{{ __('No limit') }}'"></span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-sm text-gray-700 dark:text-gray-200">
                                <div class="flex items-center gap-2"><i class="fas fa-microchip text-gray-500"></i><span
                                        x-text="product.cpu + ' {{ __('vCores') }}'"></span></div>
                                <div class="flex items-center gap-2"><i class="fas fa-memory text-gray-500"></i><span
                                        x-text="product.memory + ' {{ __('MB') }}'"></span></div>
                                <div class="flex items-center gap-2"><i class="fas fa-hdd text-gray-500"></i><span
                                        x-text="product.disk + ' {{ __('MB') }}'"></span></div>
                                <div class="flex items-center gap-2"><i class="fas fa-save text-gray-500"></i><span
                                        x-text="product.backups"></span></div>
                                <div class="flex items-center gap-2"><i class="fas fa-database text-gray-500"></i><span
                                        x-text="product.databases"></span></div>
                                <div class="flex items-center gap-2"><i
                                        class="fas fa-network-wired text-gray-500"></i><span
                                        x-text="product.allocations"></span></div>
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-400" x-text="product.description"></p>

                            <div
                                class="flex items-center justify-between text-sm font-semibold text-gray-900 dark:text-white">
                                <span x-text="'{{ __('Price') }}'"></span>
                                <span x-text="product.display_price + ' {{ $credits_display_name }}'"></span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                                <span>{{ __('Minimum') }} {{ $credits_display_name }}</span>
                                <span
                                    x-text="!product.minimum_credits ? '{{ Currency::formatForDisplay($min_credits_to_make_server) }}' : product.display_minimum_credits"></span>
                            </div>

                            <div class="mt-auto space-y-2">
                                <button type="button"
                                    :disabled="(product.minimum_credits > user.credits && product.price > user.credits) || product
                                        .doesNotFit == true || (product.servers_count >= product.serverlimit && product
                                            .serverlimit != 0) || submitClicked"
                                    class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-semibold transition border border-transparent text-white bg-accent-600 hover:bg-accent-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    @click="setProduct(product.id)"
                                    x-text="product.doesNotFit == true
                                            ? '{{ __('Server cant fit on this Location') }}'
                                            : (product.servers_count >= product.serverlimit && product.serverlimit != 0
                                                ? '{{ __('Max. Servers with configuration reached') }}'
                                                : (product.minimum_credits > user.credits && product.price > user.credits
                                                    ? '{{ __('Not enough') }} {{ $credits_display_name }}!'
                                                    : '{{ __('Create server') }}'))"></button>

                                @if (env('APP_ENV') == 'local' || $store_enabled)
                                    <template
                                        x-if="product.price > user.credits || product.minimum_credits > user.credits">
                                        <a href="{{ route('store.index') }}" class="block w-full">
                                            <button type="button"
                                                class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-semibold transition border border-yellow-500 text-yellow-800 dark:text-yellow-100 bg-yellow-100 dark:bg-yellow-900/50 hover:bg-yellow-200 dark:hover:bg-yellow-800/70">
                                                {{ __('Buy more') }} {{ $credits_display_name }}
                                            </button>
                                        </a>
                                    </template>
                                @endif
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </form>
    </section>

    <script>
        function serverApp() {
            return {
                loading: false,
                fetchedLocations: false,
                fetchedProducts: false,

                name: null,
                selectedNest: null,
                selectedEgg: null,
                selectedLocation: null,
                selectedProduct: null,
                selectedBillingPriority: null,
                locationDescription: null,

                selectedNestObject: {},
                selectedEggObject: {},
                selectedLocationObject: {},
                selectedProductObject: {},

                billingPeriodTranslations: {
                    'monthly': '{{ __('per Month') }}',
                    'half-annually': '{{ __('per 6 Months') }}',
                    'quarterly': '{{ __('per 3 Months') }}',
                    'annually': '{{ __('per Year') }}',
                    'weekly': '{{ __('per Week') }}',
                    'daily': '{{ __('per Day') }}',
                    'hourly': '{{ __('per Hour') }}'
                },

                billingPriorityLabels: {
                    @foreach (App\Enums\BillingPriority::cases() as $priority)
                        '{{ $priority->value }}': '{{ $priority->label() }}',
                    @endforeach
                },

                user: {!! $user !!},
                nests: {!! $nests !!},
                eggsSave: {!! $eggs !!},
                eggs: [],
                locations: [],
                products: [],

                submitClicked: false,

                async setEggs() {
                    this.fetchedLocations = false;
                    this.fetchedProducts = false;
                    this.locations = [];
                    this.products = [];
                    this.selectedEgg = null;
                    this.selectedLocation = null;
                    this.selectedProduct = null;
                    this.locationDescription = null;

                    this.eggs = this.eggsSave.filter(egg => egg.nest_id == this.selectedNest);

                    if (this.eggs.length === 1) {
                        this.selectedEgg = this.eggs[0].id;
                        await this.fetchLocations();
                        return;
                    }

                    this.updateSelectedObjects();
                },

                setProduct(productId) {
                    if (!productId) return;

                    const missingName = !this.name || !this.name.toString().trim();
                    const missingLocation = !this.selectedLocation;
                    if (missingName || missingLocation) {
                        const msgs = [];
                        if (missingName) msgs.push('{{ __('Please enter a server name.') }}');
                        if (missingLocation) msgs.push('{{ __('Please select a location.') }}');
                        if (window.flashMessage) {
                            window.flashMessage('error', msgs.join('<br>'));
                        }
                        this.submitClicked = false;
                        return;
                    }

                    this.selectedProduct = productId;
                    this.updateSelectedObjects();

                    const hasEmptyRequiredVariables = this.hasEmptyRequiredVariables(this.selectedEggObject.environment);

                    if (hasEmptyRequiredVariables.length > 0) {
                        this.dispatchModal(hasEmptyRequiredVariables);
                    } else {
                        document.getElementById('product').value = productId;
                        document.getElementById('serverForm').submit();
                    }
                },

                async fetchLocations() {
                    this.loading = true;
                    this.fetchedLocations = false;
                    this.fetchedProducts = false;
                    this.locations = [];
                    this.products = [];
                    this.selectedLocation = null;
                    this.selectedProduct = null;
                    this.locationDescription = null;

                    const response = await axios.get(`{{ route('products.locations.egg') }}/${this.selectedEgg}`)
                        .catch(console.error);

                    this.fetchedLocations = true;
                    this.locations = response?.data || [];

                    if (this.locations.length === 1 && this.locations[0]?.nodes?.length === 1) {
                        this.selectedLocation = this.locations[0]?.id;
                        await this.fetchProducts();
                        return;
                    }

                    this.loading = false;
                    this.updateSelectedObjects();
                },

                async fetchProducts() {
                    this.loading = true;
                    this.fetchedProducts = false;
                    this.products = [];
                    this.selectedProduct = null;

                    const response = await axios.get(
                            `{{ route('products.products.location') }}/${this.selectedEgg}/${this.selectedLocation}`)
                        .catch(console.error);

                    this.fetchedProducts = true;
                    this.products = (response?.data || []).sort((p1, p2) => parseInt(p1.price, 10) > parseInt(p2.price,
                        10) && 1 || -1);

                    this.products.forEach(product => {
                        product.cpu = product.cpu / 100;
                    });

                    if (this.products.length === 1) {
                        this.selectedProduct = this.products[0].id;
                    }

                    this.locationDescription = this.locations.find(location => location.id == this.selectedLocation)
                        ?.description ?? null;
                    this.loading = false;
                    this.updateSelectedObjects();
                },

                updateSelectedObjects() {
                    this.selectedNestObject = this.nests.find(nest => nest.id == this.selectedNest) ?? {};
                    this.selectedEggObject = this.eggs.find(egg => egg.id == this.selectedEgg) ?? {};

                    this.selectedLocationObject = this.locations.find(location => location.id == parseInt(this
                        .selectedLocation) || location.id == this.selectedLocation) ?? {};

                    this.selectedProductObject = this.products.find(product => product.id == this.selectedProduct) ?? {};
                },

                isFormValid() {
                    if (!this.name || !this.name.toString().trim()) return false;
                    if (Object.keys(this.selectedNestObject).length === 0) return false;
                    if (Object.keys(this.selectedEggObject).length === 0) return false;
                    if (Object.keys(this.selectedLocationObject).length === 0) return false;
                    if (Object.keys(this.selectedProductObject).length === 0) return false;
                    return true;
                },

                hasEmptyRequiredVariables(environment) {
                    if (!environment) return [];

                    return environment.filter((variable) => {
                        const hasRequiredRule = variable.rules?.includes('required');
                        const isDefaultNull = !variable.default_value;
                        return hasRequiredRule && isDefaultNull;
                    });
                },

                getLocationInputText() {
                    if (this.fetchedLocations) {
                        if (this.locations.length > 0) {
                            return '{{ __('Please select a location ...') }}';
                        }
                        return '{{ __('No location found matching current configuration') }}';
                    }
                    return '{{ __('---') }}';
                },

                getProductInputText() {
                    if (this.fetchedProducts) {
                        if (this.products.length > 0) {
                            return '{{ __('Please select a resource ...') }}';
                        }
                        return '{{ __('No resources found matching current configuration') }}';
                    }
                    return '{{ __('---') }}';
                },

                getEggInputText() {
                    if (this.selectedNest) {
                        return '{{ __('Please select a configuration ...') }}';
                    }
                    return '{{ __('---') }}';
                },

                getProductOptionText(product) {
                    let text = product.name + ' (' + product.description + ')';

                    if (product.minimum_credits > this.user.credits) {
                        return '{{ __('Not enough credits!') }} | ' + text;
                    }

                    return text;
                },

                dispatchModal(variables) {
                    SwalCustom.fire({
                            title: '{{ __('Required Variables') }}',
                            html: `
                          ${variables.map(variable => `
                                                                    <div class="text-left space-y-2 mb-3">
                                                                      <div class="flex justify-between items-center">
                                                                        <label for="${variable.env_variable}" class="font-medium">${variable.name}</label>
                                                                        ${variable.description ? `<i title="${variable.description}" class="fas fa-info-circle text-gray-500"></i>` : ''}
                                                                      </div>
                                                                      ${
                                                                        variable.rules.includes('in:')
                                                                          ? (() => {
                                                                            const inValues = variable.rules.match(/in:([^|]+)/)[1].split(',');
                                                                            return `
                                      <select name="${variable.env_variable}" id="${variable.env_variable}" required class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3 py-2">
                                          ${inValues.map(value => `<option value="${value}">${value}</option>`).join('')}
                                      </select>
                                    `;
                                                                          })()
                                                                          : `<input id="${variable.env_variable}" name="${variable.env_variable}" type="text" required class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3 py-2">`
                        } <
                        div id = "${variable.env_variable}-error"
                        class = "text-sm text-red-500" > < /div> < /
                        div >
                        `).join('')
                          }
                        `,
                        confirmButtonText: '{{ __('Submit') }}',
                        showCancelButton: true,
                        cancelButtonText: '{{ __('Cancel') }}',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            const filledVariables = variables.map(variable => {
                                const value = document.getElementById(variable.env_variable).value;
                                return {
                                    ...variable,
                                    filled_value: value
                                };
                            });

                            const response = await fetch('{{ route('servers.validateDeploymentVariables') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    variables: filledVariables
                                })
                            });

                            if (!response.ok) {
                                const errorData = await response.json();

                                variables.forEach(variable => {
                                    const errorContainer = document.getElementById(
                                        `${variable.env_variable}-error`);
                                    if (errorContainer) {
                                        errorContainer.innerHTML = '';
                                    }
                                });

                                if (errorData.errors) {
                                    Object.entries(errorData.errors).forEach(([key, messages]) => {
                                        const errorContainer = document.getElementById(`${key}-error`);
                                        if (errorContainer) {
                                            errorContainer.innerHTML = messages.map(message =>
                                                `<div>${message}</div>`).join('');
                                        }
                                    });
                                }

                                return false;
                            }

                            return response.json();
                        },
                    }).then((result) => {
                    if (result.isConfirmed && result.value.success) {
                        document.getElementById('egg_variables').value = JSON.stringify(result.value.variables);
                        document.getElementById('serverForm').submit();
                    }
                });
            },
        };
        }
    </script>
@endsection

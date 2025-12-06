<!-- The Modal -->
<div x-data="{ open: false }" @open-redeem-modal.window="open = true" x-show="open" x-cloak
    class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Overlay -->
    <div x-show="open" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
    <div class="flex items-center justify-center min-h-screen p-4 relative z-50">
        <!-- Modal Content -->
        <div x-show="open" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-md bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl transition-all">

            <!-- Close Button -->
            <button @click="open = false" type="button"
                class="absolute top-4 right-4 p-1.5 rounded-lg hover:bg-gray-800 text-gray-500 hover:text-gray-300 transition-colors z-10">
                <i class="fas fa-times text-base"></i>
            </button>

            <!-- Modal Header -->
            <div class="px-6 pt-6 pb-2">
                <h3 class="text-xl font-semibold text-white" id="modal-title">
                    {{ __('Redeem voucher') }}
                </h3>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-6">
                <form id="redeemVoucherForm" onsubmit="return false" method="post"
                    action="{{ route('voucher.redeem') }}">
                    <div class="space-y-4">
                        <x-form-group label="{{ __('Code') }}" for="redeemVoucherCode">
                            <x-input.text id="redeemVoucherCode" name="code" placeholder="Enter voucher code"
                                type="text" />
                            <span id="redeemVoucherCodeError" class="text-sm text-red-400 block mt-2"></span>
                            <span id="redeemVoucherCodeSuccess" class="text-sm text-green-400 block mt-2"></span>
                        </x-form-group>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-800">
                <x-button variant="secondary" @click="open = false" type="button">
                    {{ __('Cancel') }}
                </x-button>
                <x-button id="redeemVoucherSubmit" onclick="redeemVoucherCode()" type="button">
                    {{ __('Redeem') }}
                </x-button>
            </div>

        </div>
    </div>
</div>


<script>
    function redeemVoucherCode() {
        let form = document.getElementById('redeemVoucherForm')
        let button = document.getElementById('redeemVoucherSubmit')
        let input = document.getElementById('redeemVoucherCode')

        button.disabled = true

        $.ajax({
            method: form.method,
            url: form.action,
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                code: input.value
            },
            success: function(response) {
                resetForm()
                redeemVoucherSetSuccess(response)
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('close-redeem-modal'));
                    location.reload();
                }, 1500)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                resetForm()
                redeemVoucherSetError(jqXHR)
                console.error(jqXHR.responseJSON)
            },

        })
    }

    function resetForm() {
        let button = document.getElementById('redeemVoucherSubmit')
        let input = document.getElementById('redeemVoucherCode')
        let successLabel = document.getElementById('redeemVoucherCodeSuccess')
        let errorLabel = document.getElementById('redeemVoucherCodeError')

        input.classList.remove('is-invalid')
        input.classList.remove('is-valid')
        successLabel.innerHTML = ''
        errorLabel.innerHTML = ''
        button.disabled = false
    }

    function redeemVoucherSetError(error) {
        let input = document.getElementById('redeemVoucherCode')
        let errorLabel = document.getElementById('redeemVoucherCodeError')

        input.classList.remove('border-gray-600', 'focus:ring-accent-500', 'focus:border-accent-500')
        input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500')

        errorLabel.innerHTML = error.status === 422 ? error.responseJSON.errors.code[0] : error.responseJSON.message
    }

    function redeemVoucherSetSuccess(response) {
        let input = document.getElementById('redeemVoucherCode')
        let successLabel = document.getElementById('redeemVoucherCodeSuccess')

        successLabel.innerHTML = response.success
        input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500')
        input.classList.add('border-green-500', 'focus:ring-green-500', 'focus:border-green-500')
    }
</script>

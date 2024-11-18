<div>
    <x-slot name="title">
        {{ _('Invoice Verification') }}
    </x-slot>
    <x-auth-card>
        <h1 class="text-center text-3xl lg:text-4xl text-blue-700 font-bold">Verify Invoice OTP</h1>

        <div>
            @csrf
            <div class="space-y-5 py-10">
                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Before continuing, could you kindly enter the otp sent to you below?') }}
                </div>
                <div class="space-y-2">
                    <x-input
                        wire:model.blur='token'
                        type="text"
                        icon="envelope-open"
                        label="OPT"
                        placeholder="9231" />
                </div>
                <x-button wire:click='verifyInvoice' blue class="w-full py-2 md:py-2 text-xl text-white" spinner="verifyInvoice" loading-delay="short" label="Verify OTP" />
            </div>
        </div>
        </x-login-card>
</div>
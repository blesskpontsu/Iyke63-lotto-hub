<div class="">
    <x-slot name="title">
        {{ _('Register') }}
    </x-slot>
    <x-auth-card>
        <h1 class="text-center text-3xl lg:text-4xl text-blue-700 font-bold">Create a new account</h1>
        <form>
            @csrf
            <div class="space-y-5 py-10">
                <div class="space-y-4">
                    <div class="space-y-2">
                        <x-input
                            wire:model.blur='firstname'
                            type="text"
                            icon="user"
                            label="First Name"
                            placeholder="Michael"
                        />
                    </div>

                    <div class="space-y-2">
                        <x-input
                            wire:model.blur='lastname'
                            type="text"
                            icon="user"
                            label="Last Name"
                            placeholder="Smith"
                        />
                    </div>

                    <div x-data="{ phone: @entangle('phone'), countryCode: @entangle('countryCode') }" class="space-y-2">
                        <div class="flex">
                            <!-- Country Code Select Dropdown -->
                            <x-select
                                x-model="countryCode"
                                wire:model.live="countryCode"
                                label='Phone'
                                class="block flex-1 rounded-l-md border-r-0 rounded-r-none text-sm"
                                value='countryCode'
                            >
                                <x-select.option label="GH" value="+233" />
                                <x-select.option label="NG" value="+234" />
                                <x-select.option label="SA" value="+27" />
                                <x-select.option label="UK" value="+44" />
                                <x-select.option label="CI" value="+225" />
                                <x-select.option label="TG" value="+228" />
                            </x-select>
                    
                            <!-- Phone Number Input -->
                            <x-input
                                type="tel"
                                x-model.live="phone"
                                wire:model="phone"
                                placeholder="542345678"
                                class="mt-6"
                                prefix="{{ $countryCode }}"
                            />
                        </div>
                    </div>
                    

                    <div class="space-y-2">
                        <x-input
                            wire:model.blur='email'
                            type="email"
                            icon="envelope-open"
                            label="Email"
                            placeholder="kofi@example.com"
                        />
                    </div>
                    <div class="space-y-2">
                        <x-password 
                            wire:model.live='password'
                            label="Password" 
                            placeholder='********'
                        />
                    </div>
                    
                </div> 
                <x-button wire:click='register' blue class="w-full py-2 md:py-2 text-xl text-white" spinner="register" loading-delay="short" label="Register" />
            </div>
            <div>
                <p class="text-gray-500">Already have an account? <a class="ml-3 text-blue-700 hover:text-blue-900" href="/login" wire:navigate>Login</a></p>
            </div>
        </form>
    </x-login-card>

    @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('redirectAfterDelay', (event) => {
                setTimeout(function() {
                    window.location.href = '/';
                }, 10000); // 5000 milliseconds = 5 seconds
            });
    });
    </script>
    @endpush
</div>


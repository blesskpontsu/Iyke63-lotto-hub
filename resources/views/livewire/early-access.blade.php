<div class="w-full">
    <div class="xl:w-1/5 mx-auto my-16">
        <h1 class="text-center text-4xl font-bold text-blue-900">Get an Early Access</h1>
        <div class="flex justify-center items-center my-10">
            <form
                @submit.prevent="doCaptcha"
                x-data="{
                    siteKey: @js(config('services.recaptcha.site_key')),
                    init() {
                        // load our recaptcha.
                        if (!window.recaptcha) {
                            const script = document.createElement('script');
                            script.src = 'https://www.google.com/recaptcha/api.js?render=' + this.siteKey;
                            document.body.append(script);
                        }
                    },
                    doCaptcha() {
                        grecaptcha.execute(this.siteKey, {action: 'submit'}).then(token => {
                            Livewire.dispatch('formSubmitted', {token: token});
                        });
                    },
            }">
                <div class="space-y-5">
                    <x-input
                        icon="user"
                        wire:model.blur='firstname'
                        label="First Name"
                        placeholder="Stephen"
                    />

                    <x-input
                        icon="user"
                        wire:model.blur='lastname'
                        label="Last Name"
                        placeholder="Appiah"
                    />

                    <x-input
                        icon="phone"
                        wire:model.blur='phone'
                        label="Phone Number"
                        placeholder="0123456789"
                    />

                    <x-input
                        icon="envelope"
                        wire:model.blur='email'
                        label="Email"
                        placeholder="samuel@example.com"
                    />

                    <x-input
                        icon="user-group"
                        wire:model.blur='country'
                        label="country"
                        placeholder="Ghana"
                    />

                    <x-button
                        class="bg-blue-900 w-full py-4 rounded-lg"
                        type='submit'
                        spinner.longest="register"  
                        label="Get Early Access"
                    />
                </div>
            </form>
        </div>
    </div>
</div>

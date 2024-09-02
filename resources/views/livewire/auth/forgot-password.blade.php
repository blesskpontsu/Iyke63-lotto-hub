<div class="">
    <x-slot name="title">
        {{ _('Forot Password') }}
    </x-slot>
    <x-auth-card>
        <h1 class="text-center text-3xl lg:text-4xl text-blue-700 font-bold">Forgot Passowrd?</h1>
        <p class="text-center text-gray-500">No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one. </p>
        <form>
            @csrf
            <div class="space-y-5 py-10">
                <div class="space-y-4">
                    <div class="space-y-2">
                        <x-input
                            wire:model.blur='email'
                            type="email"
                            icon="envelope-open"
                            label="Email"
                            placeholder="kofi@example.com"
                        />
                    </div> 
                </div> 
                <x-button wire:click='sendPasswordResetLink' blue class="w-full py-2 md:py-2 text-xl text-white" spinner="sendPasswordResetLink" loading-delay="short" label="Email Password Reset Link" />
            </div>
            <div>
                <p class="text-gray-500">Don't have an account yet? <a class="ml-3 text-blue-700 hover:text-blue-900" href="/register" wire:navigate>Register</a></p>
            </div>
        </form>
    </x-login-card>
</div>


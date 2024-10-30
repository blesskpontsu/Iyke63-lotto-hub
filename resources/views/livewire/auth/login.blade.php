<div class="">
    <x-slot name="title">
        {{ _('Login') }}
    </x-slot>
    <x-auth-card>
        <h1 class="text-center text-3xl lg:text-4xl text-blue-700 font-bold">Sign in to Dashboard</h1>
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
                    <div class="space-y-2">
                        <x-password wire:model.blur='password' label="Password" placeholder="********" /> 
                    </div>
                    
                </div> 
                <x-button wire:click='login' blue class="w-full py-2 md:py-2 text-xl text-white" spinner="login" loading-delay="short" label="Log In" />
            </div>
            <div>
                <p class="text-gray-500">Don't have an account yet? <a class="ml-3 text-blue-700 hover:text-blue-900" href="/register" wire:navigate>Register</a></p>
            </div>
            <div>
                <p class="text-gray-500">Forgot your password? <a class="ml-3 text-blue-700 hover:text-blue-900" href="/forgot-password" wire:navigate>Reset Password</a></p>
            </div>
        </form>
    </x-login-card>
</div>


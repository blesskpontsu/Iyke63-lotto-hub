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
                    <div class="space-y-2" x-data="{ showPassword: false }">
                        <x-input wire:model.blur='password' label="Password" x-bind:type="showPassword ? 'text' : 'password'" class="password-input" placeholder="********" icon="lock-closed">
                            <x-slot name="append" @click="showPassword = !showPassword">
                                <x-button
                                    x-show="!showPassword"
                                    class="h-full"
                                    id="input-eye"
                                    icon="eye"
                                    rounded="rounded-r-md"
                                    primary
                                    flat
                                />
                                <x-button
                                    x-show="showPassword"
                                    class="h-full"
                                    id="input-eye-off"
                                    icon="eye-slash"
                                    rounded="rounded-r-md"
                                    primary
                                    flat
                                />
                            </x-slot>
                        </x-input>
                    </div>
                    
                </div> 
                <x-button wire:click='login' blue class="w-full py-2 md:py-2 text-xl text-white" spinner="login" loading-delay="short" label="Log In" />
            </div>
        </form>
    </x-login-card>
</div>


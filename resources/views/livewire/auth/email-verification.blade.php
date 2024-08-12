<div class="">
    <x-slot name="title">
        {{ _('Email Verification') }}
    </x-slot>
    <x-auth-card>
        <h1 class="text-center text-3xl lg:text-4xl text-blue-700 font-bold">Verify Email</h1>

        <div>
            @csrf
            <div class="space-y-5 py-10">
                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </div>
                
                <x-button wire:click='sendVerificationEmail' blue class="w-full py-2 md:py-2 text-xl text-white" spinner="sendVerificationEmail" loading-delay="short" label="Resend Verification Link" />
                
                <div class="text-center">
                    <p class="inline px-3">Already verified your email?</p> <a class="kauri-text-green hover-text-deep-green" href="{{ route('dashboard') }}">Dashboard</a>  
                </div>
            </div>
        </div>
    </x-login-card>
</div>


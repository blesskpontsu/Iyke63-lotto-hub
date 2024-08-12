<div>
    <x-slot name="title">
        {{ _('Settings') }}
    </x-slot>

    <x-structure>
        <div class="w-full bg-zinc-200 h-screen">
            <div class="bg-white py-5 w-full shadow-xl px-2">
                <h1>Settings</h1>
            </div>
            <div class="bg-white shadow-lg mx-auto py-8 mt-10 flex items-center justify-around">
                <figure>
                    <img width="100" src="{{ asset('images/user-image.webp') }}" alt="">
                </figure>
                <div>
                    <p class="text-xl">{{ Auth::guard('admin')->user()->firstname }}</p>
                    <p class="text-sm">{{ Auth::guard('admin')->user()->email }}</p>
                </div>
                <div>
                    <button wire:click='logout' class="">Logout</button>
                </div>
            </div>
            
        </div>
        <livewire:admin.navigation />
    </x-structure>
</div>

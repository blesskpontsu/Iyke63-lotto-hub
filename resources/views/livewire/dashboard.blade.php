<div>
    <x-slot name="title">
        {{ _('Dashboard') }}
    </x-slot>

    <x-structure>
        <div class="w-full bg-zinc-200 px-5 h-screen">
            <figure>
                <img class="mx-auto" width="150" src="{{ asset('images/logo.png') }}" alt="">
            </figure>
            <div class="bg-white w-11/12 shadow-lg mx-auto py-8 rounded-lg flex items-center">
                <figure>
                    <img width="100" src="{{ asset('images/user-image.webp') }}" alt="">
                </figure>
                <div>
                    <p class="text-xl">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</p>
                    <p class="text-sm">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="flex justify-around items-center my-10 text-white">
                <button wire:click='requestBet' style="width: 30%" class="bg-warning-500 py-6 rounded-xl px-1">
                    <i class="fa-solid fa-gear fa-2x"></i>
                    <p class="font-semibold">Stake Lotto</p>
                </button>
                <button style="width: 30%" class="bg-blue-500 py-6 rounded-xl px-1">
                    <i class="fa-solid fa-video fa-2x"></i>
                    <p class="font-semibold">Videos</p>
                </button>
                <button wire:click='lottoResults' style="width: 30%" class="bg-green-500 py-6 rounded-xl px-1">
                    <i class="fa-solid fa-gear fa-2x"></i>
                    <p class="font-semibold">Lotto Results</p>
                </button>
            </div>
            <div class="bg-white w-11/12 shadow-lg mx-auto pt-10 pb-32 rounded-lg">
                <h2 class="text-center text-3xl font-bold">Announcements</h2>
                @foreach ($announcements as $announcement)
                <div class="mt-2 px-3">
                    <h3 class="text-center text-xl font-semibold">{{ $announcement->title }}</h3>
                    <p>{{ $announcement->body }}</p>
                </div>
                @endforeach
            </div>
        </div>
        <livewire:navigation />
    </x-structure>
</div>

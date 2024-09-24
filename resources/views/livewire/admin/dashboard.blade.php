<div class="m-h-screen">
    <x-slot name="title">
        {{ _('Dashboard') }}
    </x-slot>

    <x-structure>
        @if ($selectedRequest)
            <x-Modal name="view.request" title="Update Bet Request">
                <x-slot:body>
                    <livewire:admin.edit-best-request :request="$selectedRequest" />
                </x-slot:body>
            </x-Modal>
        @endif
        <div class="w-full bg-zinc-200 px-5 min-h-screen">
            <figure>
                <img class="mx-auto" width="150" src="{{ asset('images/logo.png') }}" alt="">
            </figure>
            <div class="bg-white w-11/12 shadow-lg mx-auto py-8 rounded-lg flex items-center">
                <figure>
                    <img width="100" src="{{ asset('images/user-image.webp') }}" alt="">
                </figure>
                <div>
                    <p class="text-xl">{{ Auth::guard('admin')->user()->firstname }} {{ Auth::guard('admin')->user()->lastname }}</p>
                    <p class="text-sm">{{ Auth::guard('admin')->user()->email }}</p>
                </div>
            </div>
            <div class="flex justify-around items-center my-10 text-white">
                <button wire:click='forcast' style="width: 30%" class="bg-warning-500 py-6 rounded-xl px-1">
                    <i class="fa-solid fa-chart-line fa-2x"></i>
                    <p class="font-semibold">Forcast</p>
                </button>
                <button wire:click='videos' style="width: 30%" class="bg-blue-500 py-6 rounded-xl px-1">
                    <i class="fa-solid fa-video fa-2x"></i>
                    <p class="font-semibold">Videos</p>
                </button>
                <button wire:click='result' style="width: 30%" class="bg-green-500 py-6 rounded-xl px-1">
                    <i class="fa-solid fa-hashtag fa-2x"></i>
                    <p class="font-semibold">Lotto Results</p>
                </button>
            </div>
            <div class="bg-white shadow-lg mx-auto py-8 pb-32 rounded-lg">
                <h2 class="text-center text-3xl font-bold">Lotto Requests</h2>
            
                {{-- Table --}}
                <div class="overflow-x-auto px-3">
                    @foreach($requests as $request)
                        <div 
                            wire:key='{{ $request->id }}'
                            class="my-5 flex justify-between items-center"
                            >
                            <div>
                                <p class="text-md font-semibold">{{ $request->game }}</p>
                                <p>{{ $request->game_type }} {{ $request->game_code }}</p>
                                <p>{{ $request->selected_numbers }}</p>
                                <p>GHC{{ $request->amount }}</p>
                                <p>{{ $request->user->phone }}</p>
                            </div>
                            <div>
                                <button 
                                    wire:click="viewRequest('{{ $request->id }}')"
                                    class="bg-blue-500 text-white px-2 py-1 rounded-lg">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
        </div>
        <livewire:admin.navigation />
    </x-structure>
</div>

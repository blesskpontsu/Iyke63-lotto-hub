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
                    <p class="text-xl">{{ Auth::guard('admin')->user()->firstname }} {{ Auth::guard('admin')->user()->lastname }}</p>
                    <p class="text-sm">{{ Auth::guard('admin')->user()->email }}</p>
                </div>
            </div>
            <div class="flex justify-around items-center my-10 text-white">
                <button wire:click='forcast' style="width: 30%" class="bg-warning-500 py-6 rounded-xl px-1">
                    <i class="fa-solid fa-chart-line fa-2x"></i>
                    <p class="font-semibold">Forcast</p>
                </button>
                <button style="width: 30%" class="bg-blue-500 py-6 rounded-xl px-1">
                    <i class="fa-solid fa-video fa-2x"></i>
                    <p class="font-semibold">Videos</p>
                </button>
                <button wire:click='result' style="width: 30%" class="bg-green-500 py-6 rounded-xl px-1">
                    <i class="fa-solid fa-hashtag fa-2x"></i>
                    <p class="font-semibold">Lotto Results</p>
                </button>
            </div>
            <div class="bg-white shadow-lg mx-auto py-8 rounded-lg">
                <h2 class="text-center text-3xl font-bold">Lotto Requests</h2>
            
                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-600 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-600 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-600 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $request->user->phone }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $request->total_amount }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                        <button class="bg-blue-500 text-white px-2 py-1 rounded-lg">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
        <livewire:admin.navigation />
    </x-structure>
</div>

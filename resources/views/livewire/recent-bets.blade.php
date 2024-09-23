<div>
    <x-slot name="title">
        {{ _('Recent Bets') }}
    </x-slot>

    <x-structure>
        <div class="w-full bg-zinc-200 h-full pb-32">
            <div class="bg-white py-5 w-full shadow-xl px-2">
                <h1>Recent Bets</h1>
            </div>
            
               @foreach ($tickets as $ticket)
                <div class="bg-white shadow-lg w-11/12 mx-auto rounded-lg px-3 py-8 mt-10 flex justify-between items-center">
                    <div>
                        <p class="text-md font-bold">{{ $ticket->game }}</p>
                        <p>{{ $ticket->game_type }} {{ $ticket->game_code }}</p>
                        <p>{{ $ticket->selected_numbers }}</p>
                        <p>GHC{{ $ticket->amount }}</p>
                        <p class="font-semibold">{{ $ticket->status }}</p>
                    </div>
                    <div>
                        @if ($ticket->image)
                            <button 
                                x-on:click="$dispatch('open-modal', { name: 'modal-{{ $ticket->id }}' })">
                                Preview
                            </button>
                        @endif
                    </div>
                </div>
               @endforeach
        </div>

        @foreach ($tickets as $ticket)
            <div 
                x-data="{ show: false }"
                x-show="show"
                class="fixed inset-0 flex items-center justify-center z-50"
                x-on:open-modal.window="show = ($event.detail.name === 'modal-{{ $ticket->id }}')"
                x-on:close-modal.window="show = false"
                x-on:keydown.escape.window="show = false"
                x-transition
                style="display: none"
            >
                <!-- Overlay -->
                <div 
                    x-on:click="show = false"
                    class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm">
                </div>

                <!-- Modal Content -->
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg z-10">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center border-b pb-3">
                        <button
                            x-on:click="$dispatch('close-modal')"
                            class="text-gray-600 hover:text-gray-900">
                            ✖
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="mt-4">
                        <img src="{{ asset('storage/bet-requests/' . $ticket->image) }}" alt="img">
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end mt-4">
                        <button 
                            x-on:click="show = false"
                            class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        @endforeach


        <livewire:navigation />
    </x-structure>
</div>

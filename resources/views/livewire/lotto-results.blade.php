<div>
    <x-slot name="title">
        {{ _('Results') }}
    </x-slot>

    <x-structure>
        <div class="w-full bg-zinc-200 px-5 h-screen">
            <figure>
                <img class="mx-auto" width="150" src="{{ asset('images/logo.png') }}" alt="">
            </figure>
            <div class="bg-white shadow-lg mx-auto py-8 rounded-lg">
                <h2 class="text-center text-3xl font-bold">Today's Results</h2>
                @foreach ($results as $result)
                <div class="my-10 px-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-semibold">{{ $result->numbers }}</h2> 
                            <h2>{{ $result->company }}</h2> 
                          </div>
                          <div>
                              <p>{{ $result->game }}</p>
                          </div>
                    </div>
                    <hr class="w-full border-t-2">
                    <p class="text-right">{{ $result->date }}</p>
                </div>
                @endforeach
            </div>
        </div>
        <livewire:navigation />
    </x-structure>
</div>

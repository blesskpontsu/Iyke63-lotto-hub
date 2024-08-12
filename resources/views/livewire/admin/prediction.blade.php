<div>
    <x-slot name="title">
        {{ __('Predictions') }}
    </x-slot>
<x-structure>
    <div class="w-full bg-zinc-200 h-screen">
        <div class="bg-white py-5 w-full shadow-xl px-2">
            <h1>Predictions</h1>
        </div>
        <div class="bg-white shadow-lg mx-auto py-8 mt-10">
           @foreach ($predictions as $prediction)
               <figure class="text-center my-5">
                <h3 class="text-3xl font-semibold text-gray-600">{{ $prediction->company }}</h1>
                <h4 class="text-gray-600 text-lg">{{ $prediction->game }}</h1>
                    <h4 class="text-gray-600 text-sm">Draw time: {{ \Carbon\Carbon::parse($prediction->draw_time)->format('F j, Y g:i A') }}</h4>
                <img class="mt-3 mx-auto" width="350" src="{{ asset('storage/forcast/' . $prediction->image) }}" alt="here">
               </figure>
               
           @endforeach
        </div>
        
    </div>
    <livewire:admin.navigation />
</x-structure>
</div>
<div>
    <x-slot name="title">
        {{ __('Videos') }}
    </x-slot>
<x-structure>
    <div class="w-full bg-zinc-200 h-screen">
        <div class="bg-white py-5 w-full shadow-xl px-2">
            <h1>Videos</h1>
        </div>
        <div class="mt-10 flex justify-end mx-5">
            <button  
                class="px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600"
                wire:click="$dispatch('openModal', { component: 'admin.video.create'})"
                >
                <i class="fa-solid fa-plus"></i>
            </button> 
        </div>
        @foreach ($videos as $video)
            <div class="bg-white shadow-lg mt-5 py-8 px-3 mx-2 rounded-lg">
                <div class="flex justify-between items-center">
                    <div class="text-gray-600">
                        <p class="text-lg font-semibold">{{ $video->title }}</p>
                        <p class="text-sm">{{ $video->created_at }}</p>
                    </div>
                    <div>
                        <a href="{{ $video->url }}">Preview</a>
                    </div>
                    <div>
                        <button class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button> 
                        <button class="px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <livewire:admin.navigation />
</x-structure>
</div>
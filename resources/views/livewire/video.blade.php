<div>
    <x-slot name="title">
        {{ __('Videos') }}
    </x-slot>
    <x-structure>
        <div class="w-full bg-zinc-200 h-screen">
            <div class="bg-white py-5 w-full shadow-xl px-2">
                <h1>Videos</h1>
            </div>
            @foreach ($videos as $video)
            <div class="bg-white shadow-lg mx-auto py-8 mt-10">
                <iframe 
                    class="mx-auto"
                    width="320" 
                    height="180" 
                    src="{{ $video->embedurl }}&amp;controls=0&amp;modestbranding=1&amp;autohide=1&amp;showinfo=0" 
                    title="YouTube video player" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; encrypted-media;" 
                    referrerpolicy="strict-origin-when-cross-origin" 
                    allowfullscreen>
                </iframe>
            </div>
            @endforeach
        </div>
        <livewire:navigation />
    </x-structure>
</div>
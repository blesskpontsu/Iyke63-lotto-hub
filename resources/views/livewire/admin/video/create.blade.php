<div>
    <x-structure>
            <div class="px-5 pt-10 pb-32 w-full bg-white">
                <h1 class="text-center text-3xl lg:text-4xl text-blue-700 font-bold">Add Video</h1>
                <div class="space-y-5 py-10">
                    <div class="space-y-4">
                        <x-input
                            wire:model.live='title'
                            label="Title of Video"
                            placeholder="Thursday Prediction"
                        />
                    </div>
                    <div class="space-y-4">
                        <x-textarea
                            wire:model.live='description'
                            label="Description of Video"
                            placeholder="This is the prediction video for all games"
                        />
                    </div>
                    <div class="space-y-4">
                        <x-input
                            wire:model.live='url'
                            label="Link to Video"
                            placeholder="youtube.co.dvfswers"
                        />
                    </div>
                    <x-button wire:click='store' blue class="w-full py-2 md:py-2 text-xl text-white" spinner="submit" loading-delay="short" label="Create Video" />
                </div>
            </div>
        <livewire:admin.navigation />
    </x-structure>
</div>
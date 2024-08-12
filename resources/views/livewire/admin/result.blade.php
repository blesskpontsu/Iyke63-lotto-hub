<div class="" x-data="{ company: @entangle('company') }">
    <x-slot name="title">
        {{ __('Results') }}
    </x-slot>
    <x-structure>
        <div class="w-full bg-zinc-200 h-screen">
            <div class="bg-white py-5 w-full shadow-xl px-2">
                <h1>Results</h1>
            </div>
            <div class="px-5 mt-10 py-16 w-full bg-white">
                <h1 class="text-center text-3xl lg:text-4xl text-blue-700 font-bold">Add Result</h1>
                <form wire:submit.prevent="store">
                    @csrf
                    <div class="space-y-5 py-10">
                        <div class="space-y-4">
                            <x-select label="Select Lotto Company" wire:model.defer='company' placeholder="Select company">
                                <x-select.option label="Afriluck NLA" value="Afriluck NLA" />
                                <x-select.option label="NLA" value="NLA" />
                                <x-select.option label="Alpha Lotto" value="Alpha Lotto" />
                            </x-select>
                        </div>
                        <div class="space-y-4">
                            <x-input
                                wire:model.defer='game'
                                label="Name of Lotto"
                                placeholder="VAG Morning"
                            />
                        </div>
                        <div class="space-y-4">
                            <x-datetime-picker
                                wire:model.defer="date"
                                label="Result date"
                                placeholder="8/11/2024, 12:30PM"
                            />
                        </div>
                        <div class="space-y-4">
                            <x-input
                                wire:model.defer='numbers'
                                label="Name of Lotto"
                                placeholder="02-11-15-44-33"
                            />
                        </div>
                        <x-button type="submit" blue class="w-full py-2 md:py-2 text-xl text-white" spinner="submit" loading-delay="short" label="Create Result" />
                    </div>
                </form>
            </div>
        </div>
        <livewire:admin.navigation />
    </x-structure>
</div>
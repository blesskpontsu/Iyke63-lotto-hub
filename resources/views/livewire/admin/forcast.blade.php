<div class="" x-data="{ company: @entangle('company') }">
    <x-slot name="title">
        {{ __('Forcast') }}
    </x-slot>
    <x-structure>
        <div class="w-full bg-zinc-200 h-screen">
            <div class="bg-white py-5 w-full shadow-xl px-2">
                <h1>Forcast</h1>
            </div>
            <div class="px-5 mt-10 py-16 w-full bg-white">
                <h1 class="text-center text-3xl lg:text-4xl text-blue-700 font-bold">Add Forcast</h1>
                <form wire:submit.prevent="store">
                    @csrf
                    <div class="space-y-5 py-10">
                        <div class="space-y-4">
                            <x-select label="Select Lotto Company" wire:model.live='company' placeholder="Select company">
                                <x-select.option label="Afriluck NLA" value="Afriluck NLA" />
                                <x-select.option label="NLA" value="NLA" />
                                <x-select.option label="Alpha Lotto" value="Alpha Lotto" />
                            </x-select>
                        </div>
                        <div class="space-y-4">
                            <x-input
                                wire:model.live='game'
                                label="Name of Lotto"
                                placeholder="VAG Morning"
                            />
                        </div>
                        <div class="space-y-4">
                            <x-datetime-picker
                                wire:model.live="draw_time"
                                label="Time for Draw"
                                placeholder="8/11/2024, 12:30PM"
                            />
                        </div>
                        <div class="space-y-4">
                            <x-input
                                type='file'
                                wire:model.live='image'
                                label="Select forcast Image"
                            />
                        </div>
                        <x-button type="submit" blue class="w-full py-2 md:py-2 text-xl text-white" spinner="submit" loading-delay="short" label="Create forcast" />
                    </div>
                </form>
            </div>
        </div>
        <livewire:admin.navigation />
    </x-structure>
</div>
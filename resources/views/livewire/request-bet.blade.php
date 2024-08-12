<div class="" x-data="{ company: @entangle('company') }">
    <x-slot name="title">
        {{ __('Request Stake') }}
    </x-slot>
    <x-auth-card>
        <h1 class="text-center text-3xl lg:text-4xl text-blue-700 font-bold">Stake a bet</h1>
        <form wire:submit.prevent="submit">
            @csrf
            <div class="space-y-5 py-10">
                <div class="space-y-4">
                    <div class="space-y-2">
                        <x-select label="Select Lotto Company" wire:model.live='company' placeholder="Select company">
                            @foreach ($companies as $company)
                                <x-select.option label="{{ $company['name'] }}" value="{{ $company['name'] }}" />
                            @endforeach
                        </x-select>
                    </div>

                    <!-- Display "Select Draw Time" only if the company is "National Lottery Authority" -->
                    <div class="space-y-2" x-show="company === 'National Lottery Authority'">
                        <x-select label="Select Draw Time" wire:model.live='game_time' placeholder="What draw do you want to play for">
                            @foreach ($game_times as $time)
                                <x-select.option label="{{ $time['name'] }}" value="{{ $time['name'] }}" />
                            @endforeach
                        </x-select>
                    </div>

                    <div class="space-y-2">
                        <x-input
                            wire:model.live='game'
                            label="Name of Lotto"
                            placeholder="None"
                        />
                    </div>

                    <div class="space-y-2">
                        <x-select label="Type of Lotto" wire:model.live='game_type' placeholder="Eg Perm">
                            @foreach ($game_types as $type)
                                <x-select.option label="{{ $type['name'] }}" value="{{ $type['name'] }}" />
                            @endforeach
                        </x-select>
                    </div>

                    <div class="space-y-2">
                        <x-select label="Type of Lotto" wire:model.live='game_code' placeholder="PERM 2">
                            @foreach ($codes as $code)
                                <x-select.option label="{{ $code['name'] }}" value="{{ $code['code'] }}" />
                            @endforeach
                        </x-select>
                    </div>

                    <div class="space-y-2">
                        <x-input
                            wire:model.live='selected_numbers'
                            label="Enter your numbers"
                            placeholder="07-22-11-33-44"
                        />
                    </div>

                    <div class="space-y-2">
                        <x-input
                            wire:model.live='amount'
                            label="Enter amount to stake"
                            placeholder="10"
                        />
                    </div>
                    <div class="space-y-2">
                        <h1 class="text-lg">Total Amount: {{ $total_amount }}</h1>
                    </div>
                    
                </div> 
                <x-button type="submit" blue class="w-full py-2 md:py-2 text-xl text-white" spinner="submit" loading-delay="short" label="Stake Lotto" />
            </div>
        </form>
    </x-auth-card>
    <livewire:navigation />
</div>
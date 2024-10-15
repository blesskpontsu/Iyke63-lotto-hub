
{{-- $disabled variable is set to true if there are errors or empty input fields. --}}
@php
    $disabled = $errors->any() 
@endphp

<div x-data="{
    company: @entangle('company'),
    game_time: @entangle('game_time'),
    game: @entangle('game'),
    game_type: @entangle('game_type'),
    game_code: @entangle('game_code'),
    total_amount: @entangle('total_amount'),
    amount: @entangle('amount'),
    game_times: [],
    game_types: [],
    codes: [],
    dayOfWeek: '',
    companies: ['Afriluck NLA', 'Alpha Lotto', 'National Lottery Authority'], 
    init() {
        this.dayOfWeek = this.getDayOfWeek();
        this.$watch('company', value => {
            this.updateGameTimes();
            this.updateGameTypes();
            this.updateCodes();
            this.setGame();
        });

        this.$watch('game_time', value => {
            this.setGame(); // Update game based on game_time change
        });
        
        this.$watch('game_type', value => {
            this.updateCodes();
        });

        this.$watch('game_code', value => {
            console.log('Game code changed:', value);
            this.calculateMegaAmount();
            this.calculateTotal();
        });

        this.$watch('amount', value => {
            this.calculateTotal();
        });
        
    },
    getDayOfWeek() {
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const currentDate = new Date();
        return days[currentDate.getDay()]; // Get the day of the week (0-6)
    },


    setGame() {
        let games = {};
        
        if (this.company === 'National Lottery Authority') {
            if (this.game_time === 'morning') {
                games = {
                    'Monday': 'VAG MONDAY',
                    'Tuesday': 'VAG TUESDAY',
                    'Wednesday': 'VAG WEDNESDAY',
                    'Thursday': 'VAG THURSDAY',
                    'Friday': 'VAG FRIDAY',
                    'Saturday': 'VAG SATURDAY',
                    'Sunday': 'VAG SUNDAY'
                };
            } else if (this.game_time === 'afternoon') {
                games = {
                    'Monday': 'NOON RUSH MONDAY',
                    'Tuesday': 'NOON RUSH TUESDAY',
                    'Wednesday': 'NOON RUSH WEDNESDAY',
                    'Thursday': 'NOON RUSH THURSDAY',
                    'Friday': 'NOON RUSH FRIDAY',
                    'Saturday': 'NOON RUSH SATURDAY',
                    'Sunday': 'NOON RUSH SUNDAY'
                };
            } else if (this.game_time === 'evening') {
                games = {
                    'Monday': 'MONDAY SPECIAL',
                    'Tuesday': 'LUCKY TUESDAY',
                    'Wednesday': 'MIDWEEK',
                    'Thursday': 'FORTUNE THURSDAY',
                    'Friday': 'FRIDAY BONANZA',
                    'Saturday': 'NATIONAL WEEKLY LOTTO',
                    'Sunday': 'SUNDAY ASEDA'
                };
            }
        } else if (this.company === 'Alpha Lotto') {
            games = {
                'Monday': 'ALPHA MONDAY',
                'Tuesday': 'DELTA TUESDAY',
                'Wednesday': 'OMEGA WEDNESDAY',
                'Thursday': 'EXCEL THURSDAY',
                'Friday': 'PRIME FRIDAY',
                'Saturday': 'KENSTAR SATURDAY',
                'Sunday': 'PRECISE SUNDAY'
            };
        }  else if (this.company === 'Afriluck NLA') {
            if (this.game_time === 'morning') {
                games = {
                    'Monday' : 'ANOPA MONDAY',
                    'Tuesday' : 'ANOPA TUESDAY',
                    'Wednesday' : 'ANOPA WEDNESDAY',
                    'Thursday' : 'ANOPA THURSDAY',
                    'Friday' : 'ANOPA FRIDAY',
                    'Saturday' : 'ANOPA SATURDAY',
                    'Sunday' : 'ANOPA SUNDAY'
                }
            }  else if (this.game_time === 'evening') {
                games = {
                    'Monday' : '6/57 MONDAY',
                    'Tuesday' : '6/57 TUESDAY',
                    'Wednesday' : '6/57 WEDNESDAY',
                    'Thursday' : '6/57 THURSDAY',
                    'Friday' : '6/57 FRIDAY',
                    'Saturday' : '6/57 SATURDAY',
                    'Sunday' : '6/57 SUNDAY'
                }  
            }
        }

        // Get the current day of the week
        const currentDay = this.dayOfWeek;

        // Set the game based on the current day
        this.game = games[currentDay] || 'No game available'; // Default message if day is not found
    },


    updateGameTimes() {
        const currentTime = new Date();
    
        if (this.company === 'National Lottery Authority') {
            const morningCutoff = new Date();
            morningCutoff.setHours(9, 55, 0, 0);
    
            const afternoonCutoff = new Date();
            afternoonCutoff.setHours(13, 20, 0, 0);
    
            const eveningCutoff = new Date();
            eveningCutoff.setHours(18, 55, 0, 0);
    
            if (currentTime < morningCutoff) {
                this.game_times = ['morning', 'afternoon', 'evening'];
            } else if (currentTime < afternoonCutoff) {
                this.game_times = ['afternoon', 'evening'];
            } else if (currentTime < eveningCutoff) {
                this.game_times = ['evening'];
            }
        } else if (this.company === 'Afriluck NLA') {
            const morningCutoff = new Date();
            morningCutoff.setHours(9, 55, 0, 0);
    
            const eveningCutoff = new Date();
            eveningCutoff.setHours(19, 55, 0, 0);
    
            if (currentTime < morningCutoff) {
                this.game_times = ['morning', 'evening'];
            } else if (currentTime < eveningCutoff) {
                this.game_times = ['evening'];
            }
        }
    },
    
    updateGameTypes() {
        if (this.company === 'Afriluck NLA') {
            this.game_types = this.game.includes('ANOPA') 
                ? ['Direct', 'Perm', 'Banker'] 
                : ['Mega Jackpot', 'Direct', 'Perm', 'Banker'];
        } else {
            this.game_types = ['Direct', 'Perm', 'Banker'];
        }
    },
    updateCodes() {
        if (this.game_type === 'Mega Jackpot') {
            this.codes = [
                { name: 'Mega Jackpot 5GHC', code: '5' },
                { name: 'Mega Jackpot 10GHC', code: '10' },
                { name: 'Mega Jackpot 20GHC', code: '20' },
            ];
        } else if (this.game_type === 'Direct') {
            this.codes = [
                { name: 'DIRECT 2', code: '2' },
                { name: 'DIRECT 3', code: '3' },
            ];
        } else if (this.game_type === 'Perm') {
            this.codes = [
                { name: 'PERM 2', code: '2' },
                { name: 'PERM 3', code: '3' },
            ];
        } else if (this.game_type === 'Banker') {
            this.codes = [{ name: 'Banker Against', code: '2' }];
        } else {
            this.codes = [];
        }
    },
    
    calculateMegaAmount() {
        if (this.game_code == 5) {
            this.amount = 5
        } else if (this.game_code == 10) {
            this.amount = 10
        } else if (this.game_code == 20) {
          this.amount = 20
        } 
    },

    calculateTotal() {
        this.$wire.calculatePermutations();
    }
}">
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
                        <label class="text-gray-600" for="company">Select Lotto Company</label>
                        <select class="rounded-md border-1 border-gray-300 shadow-sm shadow-gray-300 w-full" x-model="company" class="form-select">
                            <option class="text-gray-500" value="" class="text-gray-400" disabled selected>...</option> <!-- Default option -->
                            <template x-for="(companyItem, index) in companies" :key="index">
                                <option x-text="companyItem" :value="companyItem" />
                            </template>
                        <select>
                            <div class="text-red-800">@error('company') {{ $message }} @enderror</div>
                    </div>

                    <!-- Display "Select Draw Time" only if the company is "National Lottery Authority" -->
                    <div class="space-y-2" x-show="company === 'National Lottery Authority' || company === 'Afriluck NLA'" x-transition>
                        <label class="text-gray-600" for="game_time">Select Draw Time</label>
                        <select class="rounded-md border-1 border-gray-300 shadow-sm shadow-gray-300 w-full" label="Select Draw Time" x-model="game_time" placeholder="What draw do you want to play for">
                            <option class="text-gray-500" value="" disabled selected>...</option> <!-- Default option -->
                            <template x-for="(time, index) in game_times" :key="index">
                                <option :value="time" x-text="time"></option>
                            </template>
                        <select>
                        <div class="text-red-800">@error('game_time') {{ $message }} @enderror</div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-gray-600" for="game">Name of Lotto</label>
                        <input
                            class="rounded-md border-1 border-gray-300 shadow-sm shadow-gray-300 w-full"
                            type="text"
                            x-model='game'
                            placeholder="None"
                        />
                        <div class="text-red-800">@error('game') {{ $message }} @enderror</div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-gray-600" for="game_type">Type of Lotto (eg. Perm)</label>
                        <select class="rounded-md border-1 border-gray-300 shadow-sm shadow-gray-300 w-full" x-model='game_type' placeholder="Eg Perm">
                            <option class="text-gray-500" value="" disabled selected>...</option> <!-- Default option -->
                            <template x-for="(game_type, index) in game_types" :key="index">
                                <option :value="game_type" x-text="game_type"></option>
                            </template>
                        </select>
                        <div class="text-red-800">@error('game_type') {{ $message }} @enderror</div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-gray-600" for="game_code">Type of Lotto (eg. Perm 2)</label>
                        <select class="rounded-md border-1 border-gray-300 shadow-sm shadow-gray-300 w-full" x-model='game_code'>
                            <option class="text-gray-500" value="" disabled selected>...</option> <!-- Default option -->
                            <template x-for="(code, index) in codes" :key="index">
                                <option :value="code['code']" x-text="code['name']"></option>
                            </template>
                        </select>
                        <div class="text-red-800">@error('game_code') {{ $message }} @enderror</div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-gray-600" for="selected_numbers">Select your numbers</label>
                        <input
                            class="rounded-md border-1 border-gray-300 shadow-sm shadow-gray-300 w-full"
                            type="text"
                            wire:model.live='selected_numbers'
                            placeholder="eg. 07-22-11-33-44"
                        />
                        <div class="text-red-800">@error('selected_numbers') {{ $message }} @enderror</div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-gray-600" for="amount">Enter amount to stake</label>
                        <input
                            class="rounded-md border-1 border-gray-300 shadow-sm shadow-gray-300 w-full"
                            type="text"
                            x-model='amount'
                            placeholder="10"
                        />
                        <div class="text-red-800">@error('amount') {{ $message }} @enderror</div>
                    </div>
                    <div class="space-y-2">
                        <h1 class="text-lg">Total Amount: <span x-text="total_amount"></span></h1>
                    </div>
                    
                </div> 
                <x-button type="submit" blue spinner="submit" loading-delay="short" label="Stake Lotto" :disabled="$disabled" class="w-full py-2 md:py-2 text-xl text-white disabled disabled:opacity-15 transition {{ $disabled ? 'disabled' : '' }}" />
            </div>
        </form>
    </x-auth-card>
    <livewire:navigation />
</div>
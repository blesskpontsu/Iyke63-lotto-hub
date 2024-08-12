<section>
    <x-slot name="title">
        {{ _('Plans') }}
    </x-slot>
    <div class="bg-gray-100 pt-20 lg:py-44 px-5 xl:px-48">
        <h2 class="text-3xl text-center font-bold lg:text-5xl">Pick your Perfect Plan</h2>
        <p class="text-center text-md lg:text-xl py-5">Choose from our customized plans for your needs</p>
        <div class="w-full py-10 space-y-10 xl:space-y-0 xl:flex">
            <div class="bg-blue-800 pt-3 w-11/12 xl:w-1/4 mx-auto rounded-t-lg shadow-lg">
                <div class="bg-white px-10 py-10">
                    <div>
                        <h2 class="text-4xl text-left font-bold">Basic</h2>
                        <p class="text-left text-gray-500">Most Popular</p>
                    </div>
                    <div class="mt-10 space-y-10">
                        <h2 class="text-4xl font-black">GH₵70.00<span class=" font-light text-sm">/3 months</span></h2>
                        <x-button wire:click='subscribe(1)' blue class="w-full py-2 md:py-2 text-xl text-white" spinner="login" loading-delay="short" label="Choose Plan" />
                    </div>
                    <div class="mt-10 mb-24 text-left">
                        <h3 class="font-bold">What's included?</h3>
                        <ul class="mt-5 space-y-5 text-sm">
                            <li>3 Months Subscription</li>
                            <li>Daily lotto forcast</li>
                            <li>Daily Prediction Video</li>
                            <li>Betting Request</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="bg-blue-800 pt-3 w-11/12 xl:w-1/4 mx-auto rounded-t-lg shadow-lg">
                <div class="bg-white px-10 py-10">
                    <div>
                        <h2 class="text-4xl text-left font-bold">Deluxe</h2>
                        <p class="text-left text-gray-500">Regular Star</p>
                    </div>
                    <div class="mt-10 space-y-10">
                        <h2 class="text-4xl font-black">GH₵120.00<span class=" font-light text-sm">/6 months</span></h2>
                        <x-button wire:click='subscribe(2)' blue class="w-full py-2 md:py-2 text-xl text-white" spinner="login" loading-delay="short" label="Choose Plan" />
                    </div>
                    <div class="mt-10 mb-24 text-left">
                        <h3 class="font-bold">What's included?</h3>
                        <ul class="mt-5 space-y-5 text-sm">
                            <li>6 Months Subscription</li>
                            <li>Daily lotto forcast</li>
                            <li>Daily Prediction Video</li>
                            <li>Betting Request</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="bg-blue-800 pt-3 w-11/12 xl:w-1/4 mx-auto rounded-t-lg shadow-lg">
                <div class="bg-white px-10 py-10">
                    <div>
                        <h2 class="text-4xl text-left font-bold">Gold</h2>
                        <p class="text-left text-gray-500">Most Economical</p>
                    </div>
                    <div class="mt-10 space-y-10">
                        <h2 class="text-4xl font-black">GH₵150.00<span class=" font-light text-sm">/year</span></h2>
                        <x-button wire:click='subscribe(3)' blue class="w-full py-2 md:py-2 text-xl text-white" spinner="login" loading-delay="short" label="Choose Plan" />
                    </div>
                    <div class="mt-10 mb-24 text-left">
                        <h3 class="font-bold">What's included?</h3>
                        <ul class="mt-5 space-y-5 text-sm">
                            <li>1 Year Subscription</li>
                            <li>Daily lotto forcast</li>
                            <li>Daily Prediction Video</li>
                            <li>Betting Request</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

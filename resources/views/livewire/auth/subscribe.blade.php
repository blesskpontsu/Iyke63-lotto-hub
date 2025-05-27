<div class="py-10">
    <h1 class="text-2xl text-center">Choose the method with which you wish to make payment</h1>
    <div class="space-y-10 px-20 py-10">
        <div>
            <button wire:click="momoSubscription"><img class="rounded-xl mx-auto" src="{{ asset('images/momo.jpg') }}"
                    alt=""></button>
            <p class="text-center text-xl">Pay with Mobile Money (GHANAIANS ONLY)</p>
        </div>
        <div>
            <button wire:click="card_subscription"><img class="rounded-xl mx-auto" src="{{ asset('images/credit.png') }}"
                    alt=""></button>
            <p class="text-center text-xl">Pay with credit card</p>
        </div>
    </div>
</div>

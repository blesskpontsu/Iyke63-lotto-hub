<div>
    <x-slot name="title">
        {{ _('Profile') }}
    </x-slot>

    <x-structure>
        <div class="w-full bg-zinc-200 h-screen">
            <div class="bg-white py-5 w-full shadow-xl px-2">
                <h1>Profile</h1>
            </div>
            <div class="bg-white shadow-lg mx-auto py-8 mt-10 px-5">
                <div class="space-y-4">
                    <div class="space-y-2">
                        <x-input
                            wire:model.blur='firstname'
                            type="text"
                            icon="user"
                            label="First Name"
                            placeholder="Kofi" />
                    </div>
                    <div class="space-y-2">
                        <x-input
                            wire:model.blur='lastname'
                            type="text"
                            icon="user"
                            label="Last Name"
                            placeholder="Manu" />
                    </div>

                    <div class="space-y-2">
                        <x-input
                            wire:model.blur='email'
                            type="email"
                            icon="envelope-open"
                            label="Email"
                            placeholder="kofi@example.com" />
                    </div>

                    <div class="space-y-2">
                        <x-phone
                            wire:model.blur='phone'
                            icon="phone"
                            label="Phone"
                            mask="### (##) ### ####"
                            placeholder="23354xxxxxxx" />
                    </div>

                    <div class="pt-10 px-4 text-center">
                        <hr class=" border-2 border-gray-300 mb-3">
                        This Section is for Ghanaians only
                        At the moment, only MTN and TELCEL is supported by our subscription Service
                    </div>
                    <div class="space-y-2">
                        <x-select wire:model='channel' label="Select Network" placeholder="Select your mobile network">
                            <x-select.option label="MTN" value="mtn_gh_rec" />
                            <x-select.option label="TELECEL" value="vodafone_gh_rec" />
                        </x-select>
                    </div>

                    <x-button wire:click='updateInformation' blue class="py-2 md:py-2 text-lg text-white" spinner="updateInformation" loading-delay="short" label="Update Information" />
                </div>
            </div>

        </div>
        <livewire:navigation />
    </x-structure>
</div>
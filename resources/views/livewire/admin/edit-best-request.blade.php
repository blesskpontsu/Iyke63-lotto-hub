<div class="w-full">
    <div class="flex flex-col">
        <x-input
            type='file'
            wire:model.live='image'
            label="Lotto Slip"
            placeholder="Slip"
        />

        <x-button class="mt-10" wire:click='update' green right-icon="check" spinner='update' label="Update Bet" />
    </div>
</div>


<div class="w-full" x-data="{ disabled: @entangle('disabled') }">
    <div wire:loading.flex class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <!-- Warning spinner -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" class="size-16 fill-amber-500 motion-safe:animate-spin dark:fill-amber-500">
          <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
          <path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" />
        </svg>
    </div>

    <div class="flex flex-col">
        <x-input
            type='file'
            wire:model.live='image'
            label="Lotto Slip"
            placeholder="Slip"
        />

        <x-button 
            wire:click='update' 
            green 
            right-icon="check" 
            spinner="update" 
            loading-delay="short" 
            label="Update Bet" 
            :disabled="$disabled" 
            class="mt-10 disabled:opacity-15 transition"
        />
    </div>
</div>

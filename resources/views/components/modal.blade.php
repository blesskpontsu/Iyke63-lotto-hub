
@props(['title', 'name'])
<!-- Modal Component -->
<div 
    x-data = "{show : false, name: '{{ $name }}'}"
    x-show = "show"
    class="fixed inset-0 flex items-center justify-center z-50"
    x-on:open-modal.window = "show = ($event.detail.name === name)"
    x-on:close-modal.window = "show = false"
    x-on:keydown.escape.window = "show = false"
    x-transition
    style="display: none"
    >
    <!-- Overlay -->
    <div 
        x-on:click = "show = false"
        class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>

    <!-- Modal Content -->
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg z-10">
        <!-- Modal Header -->
        <div class="flex justify-between items-center border-b pb-3">
            @if (isset($title))
            <h3 class="text-xl font-semibold">{{ $title }}</h3> 
            @endif 
            <button
                x-on:click = "$dispatch('close-modal')"
                class="text-gray-600 hover:text-gray-900">
                ✖
            </button>
        </div>

        <!-- Modal Body -->
        <div class="mt-4">
            {{ $body }}
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end mt-4">
            <button 
                x-on:click = "show = false"
                class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600"
            >
                Close
            </button>
        </div>
    </div>
</div>

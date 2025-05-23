<div>
    @if($show)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="modal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $title }}</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">{{ $message }}</p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button wire:click="confirm" class="px-4 py-2 bg-blue-500 text-white rounded-md">Confirm</button>
                        <button wire:click="cancel" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md ml-2">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

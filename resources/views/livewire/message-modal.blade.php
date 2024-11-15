<div>
    @if($isOpen)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg w-1/3">
                <h2 class="text-lg font-semibold mb-4">{{ $header }}</h2>
                <p>{{ $message }}</p>
                <button wire:click="closeModal" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">إغلاق</button>
            </div>
        </div>
    @endif
</div>

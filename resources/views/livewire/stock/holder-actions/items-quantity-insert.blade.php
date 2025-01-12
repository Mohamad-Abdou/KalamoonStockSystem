<div class="flex gap-4 w-full flex-col">
    <div class="flex items-center gap-4 border-b-2 border-gray-400 py-2 justify-between">
        <x-text-input wire:model.live="search" type="search" placeholder="البحث بالاسم" />
        <div>
            <label for="details">تثبيت التفاصيل</label>
            <x-text-input wire:model.live="details" type="search" placeholder="رقم الفاتورة مثلا" />
        </div>
    </div>
    <div class="flex w-full flex-wrap">
        @foreach ($items as $key => $item)
            <div class="basis-1/5 p-4">
                <x-card wire:click="selectItem({{ $item->id }})"
                    class="flex flex-col h-full items-center border-4 border-second-color rounded cursor-pointer transition-transform duration-200 hover:scale-105 bg-primary }}">
                    <div class="mb-2">
                        <x-slot:header class="mb-5 text-black">
                            {{ $item->name }} ( {{ $item->unit }} )
                        </x-slot:header>
                    </div>
                    <p class="w-full text-gray-100 border-t-2 rounded border-white pt-2">
                        {{ $item->description }}
                    </p>
                </x-card>
            </div>
        @endforeach
    </div>

    <!-- Quantity Modal -->
    @if ($showQuantityModal)
        <div class="fixed inset-0 flex items-center justify-center z-100 bg-black bg-opacity-50 ">
            <div
                class="flex flex-col gap-3 justify-center items-center bg-white p-6 rounded shadow-lg w-1/3 border-second-color">
                <h2 class="text-lg font-semibold mb-4 text-center"> ({{ $selectedItem->unit }})
                    {{ $selectedItem->name }}
                </h2>
                <p>
                    {{ $selectedItem->description }}
                </p>
                <div class="flex flex-row border-t-4 gap-4 border-primary pt-4">
                    <div class="flex flex-col w-1/3">
                        <label for="quantity">الكمية</label>
                        <x-text-input wire:model.live="quantity" wire:keydown.enter="submitQuantity" type="number" />
                        @error('quantity')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col w-full">
                        <label for="details">التفاصيل</label>
                        <x-text-input wire:model.live="details" value='{{ $details }}' wire:keydown.enter="submitQuantity" type="search"
                            placeholder="رقم الفاتور, نقل وغيرها" />
                        @error('details')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-between w-full">
                    <button wire:click="closeModal" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">إغلاق</button>
                    <button wire:click="submitQuantity"
                        class="mt-4 bg-green-500 text-white px-4 py-2 rounded">إدخال</button>
                </div>
            </div>
        </div>
    @endif
    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>

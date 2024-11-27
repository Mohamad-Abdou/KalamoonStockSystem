<div class="flex items-center justify-center">
    <div class="basis-3/4 space-y-4">
        <div>
            <x-input-dropdown-list wire:change="addItem($event.target.value)" class="w-full" name="item_id"
                placeholder="">
                <option value="" disabled selected hidden>اختر مادة</option>
                @foreach ($itemsToRequest as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </x-input-dropdown-list>
        </div>
        <x-table.table>
            <thead class="bg-gray-100 text-gray-700 text-center">
                <tr>
                    <x-table.table-header-element>اسم المادة</x-table.table-header-element>
                    <x-table.table-header-element>وصف المادة</x-table.table-header-element>
                    <x-table.table-header-element>الكمية المطلوبة</x-table.table-header-element>
                    <x-table.table-header-element>الحذف من الطلب</x-table.table-header-element>
                </tr>
            </thead>
            <tbody>
                @foreach ($selectedItems as $id => $details)
                    <tr>
                        <x-table.data class="text-center">{{ $details['name'] }}</x-table.data>
                        <x-table.data>{{ $details['description'] }}</x-table.data>
                        <x-table.data >
                            <x-text-input type="number" wire:model="selectedItems.{{ $id }}.quantity"  class="w-full"/>
                        </x-table.data>
                        <x-table.data>
                            <x-danger-button wire:click="removeItem({{ $id }})">إزالة</x-danger-button>
                        </x-table.data>
                    </tr>
                @endforeach
            </tbody>
        </x-table.table>

        <!-- Save button -->
        <x-primary-button wire:click="saveRequest">
            حفظ كمسودة
        </x-primary-button>
    </div>
</div>

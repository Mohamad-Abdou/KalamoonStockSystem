<div class="flex items-center justify-center w-full">
    <div class="w-full space-y-4">
        <div>
            <div class="relative">
                <x-text-input 
                    wire:model.live="search"
                    wire:focus="$set('showDropdown', true)"
                    type="text" 
                    class="w-full"
                    placeholder="البحث عن مادة..."
                />
                
                @if($showDropdown)
                    <div class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg">
                        <ul class="py-1 max-h-60 overflow-y-auto">
                            @foreach($this->filteredItems as $item)
                                <li>
                                    <button
                                        wire:click="addItem({{ $item->id }})"
                                        class="w-full px-4 py-2 text-right hover:bg-gray-100"
                                    >
                                        {{ $item->name }} ({{ $item->unit }}) الوصف:  {{ $item->description }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <x-table.table>
            <thead class="bg-gray-100 text-gray-700 text-center">
                <tr>
                    <x-table.table-header-element rowspan="2">اسم المادة</x-table.table-header-element>
                    <x-table.table-header-element rowspan="2" class="w-1">الوحدة</x-table.table-header-element>
                    <x-table.table-header-element rowspan="2" class="w-1/3">وصف المادة</x-table.table-header-element>
                    <x-table.table-header-element colspan="4">الكمية المطلوبة</x-table.table-header-element>
                    <x-table.table-header-element rowspan="2" class="w-1">الحذف من الطلب</x-table.table-header-element>
                </tr>
                <tr>
                    <x-table.table-header-element>الفصل الأول</x-table.table-header-element>
                    <x-table.table-header-element>الفصل الثاني</x-table.table-header-element>
                    <x-table.table-header-element>الفصل الصيفي</x-table.table-header-element>
                    <x-table.table-header-element>الكلي</x-table.table-header-element>
                </tr>
            </thead>
            <tbody>
                @foreach ($selectedItems as $id => $details)
                    <tr>
                        <x-table.data class="text-center">{{ $details['name'] }}</x-table.data>
                        <x-table.data class="w-1" >{{ $details['unit'] }}</x-table.data>
                        <x-table.data>{{ $details['description'] }}</x-table.data>

                        <x-table.data >
                            <x-text-input type="number" wire:model.blur="selectedItems.{{ $id }}.first_semester_quantity" class="w-full"/>
                        </x-table.data>
                        <x-table.data >
                            <x-text-input type="number" wire:model.blur="selectedItems.{{ $id }}.second_semester_quantity" class="w-full"/>
                        </x-table.data>
                        <x-table.data >
                            <x-text-input type="number" wire:model.blur="selectedItems.{{ $id }}.third_semester_quantity" class="w-full"/>
                        </x-table.data>
                        <x-table.data >
                            {{ $details['total_quantity'] }}
                        </x-table.data>

                        <x-table.data class="w-1">
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

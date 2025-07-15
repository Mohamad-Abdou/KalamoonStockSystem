<div class="flex flex-col gap-10 items-center justify-center w-full">
    @if ($annualRequest->return_reason)
        <div class="basis-1/3 flex flex-col mx-5 justify-center space-y-2 bg-red-100 p-5 border rounded-xl shadow-xl"
            style="position: sticky; top: 1rem; height: fit-content">
            <h1 class="text-xl font-bold text-center"> سبب الإرجاع </h1>
            <hr class="border-2 border-gray-500 rounded-lg" />
            <p>{{ $annualRequest->return_reason }}</p>
        </div>
    @endif
    <section class="bg-white shadow-sm sm:rounded-lg basis-3/4">
        <div class="p-6 text-gray-900 flex flex-col space-y-5">
            <div class="relative">
                <x-text-input wire:model.live="search" wire:focus="$set('showDropdown', true)" type="text" class="w-full" placeholder="البحث عن مادة..." />
                
                @if ($showDropdown)
                    <div class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg">
                        <ul class="py-1 max-h-60 overflow-y-auto">
                            @foreach ($this->filteredItems as $item)
                                <li>
                                    <button wire:click="addItem({{ $item->id }})"
                                        class="w-full px-4 py-2 text-right hover:bg-gray-100">
                                        {{ $item->name }} ({{ $item->unit }}) | {{ $item->description }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <x-table.table>
                <thead class="bg-gray-100 text-gray-700 text-center">
                    <tr>
                        <x-table.table-header-element rowspan="2">اسم المادة</x-table.table-header-element>
                        
                        <x-table.table-header-element rowspan="2"
                            class="w-1">الوحدة</x-table.table-header-element>
                        <x-table.table-header-element rowspan="2" class="w-1/4">وصف
                            المادة</x-table.table-header-element>
                        <x-table.table-header-element colspan="4" class="w-1/3">الكمية المطلوبة</x-table.table-header-element>
                        <x-table.table-header-element rowspan="2" class="w-1">الحذف من
                            الطلب</x-table.table-header-element>
                        @if ($annualRequest->return_reason)
                            <x-table.table-header-element rowspan='2' class="w-1/5">ملاحظات
                                الإرجاع</x-table.table-header-element>
                        @endif
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
                        <tr
                            class="{{ $annualRequest->items->where('id', $id)->first()?->pivot->objection_reason ? 'bg-red-100' : '' }}">
                            <x-table.data class="text-center w-1/5">{{ $details['name'] }}</x-table.data>
                            <x-table.data class="text-center w-1">{{ $details['unit'] }}</x-table.data>
                            <x-table.data>{{ $details['description'] }}</x-table.data>
                            <x-table.data>
                                <x-text-input class="w-full" type="number"
                                    wire:model.live.debounce.500ms="selectedItems.{{ $id }}.first_semester_quantity"
                                    min="0" step="1" />
                            </x-table.data>
                            <x-table.data>
                                <x-text-input class="w-full" type="number"
                                    wire:model.live.debounce.500ms="selectedItems.{{ $id }}.second_semester_quantity"
                                    min="0" step="1" />
                            </x-table.data>
                            <x-table.data>
                                <x-text-input class="w-full" type="number"
                                    wire:model.live.debounce.500ms="selectedItems.{{ $id }}.third_semester_quantity"
                                    min="0" step="1" />
                            </x-table.data>
                            <x-table.data class="text-center">
                                {{ $details['total_quantity'] }}
                            </x-table.data>
                            <x-table.data class="w-1 whitespace-nowrap">
                                <div class="flex justify-center">
                                    <x-danger-button wire:click="removeItem({{ $id }})">
                                        إزالة
                                    </x-danger-button>
                                </div>
                            </x-table.data>
                            @if ($annualRequest->return_reason)
                                <x-table.data class="w-1/5">
                                    {{ $annualRequest->items->where('id', $id)->first()?->pivot->objection_reason }}
                                </x-table.data>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            <div class="flex justify-between">
                <x-primary-button wire:click="saveRequest" class="flex-start">
                    حفظ التعديلات
                </x-primary-button>
                @error('selectedItems.*.total_quantity')
                    <div class="text-red-500">
                        لا يمكن حفظ الطلب لوجود مادة بدون كمية، يرجى مراجعة الطلب
                    </div>
                @enderror
                <x-secondary-button wire:click="passRequest" class="flex-start bg-green-500">
                    إرسال الطلب
                </x-secondary-button>
            </div>
        </div>

    </section>

</div>

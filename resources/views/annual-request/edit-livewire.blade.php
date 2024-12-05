<div class="flex items-start justify-center w-full">
    <section class="bg-white shadow-sm sm:rounded-lg basis-3/4">
        <div class="p-6 text-gray-900 flex flex-col space-y-5">
            <x-input-dropdown-list wire:change="addItem($event.target.value)" class="w-full" name="item_id" placeholder="">
                <option value="" disabled selected hidden>اختر مادة</option>
                @foreach ($itemsToRequest as $item)
                    <option value="{{ $item->id }}">{{ $item->name }} -> {{ $item->description }}</option>
                @endforeach
            </x-input-dropdown-list>

            <x-table.table>
                <thead class="bg-gray-100 text-gray-700 text-center">
                    <tr>
                        <x-table.table-header-element class="w-1/5">اسم المادة</x-table.table-header-element>
                        <x-table.table-header-element class="w-1">الوحدة</x-table.table-header-element>
                        <x-table.table-header-element >وصف المادة</x-table.table-header-element>
                        <x-table.table-header-element class="w-1/6">الكمية المطلوبة</x-table.table-header-element>
                        <x-table.table-header-element class="w-1">الحذف من الطلب</x-table.table-header-element>
                        @if ($annualRequest->return_reason)
                            <x-table.table-header-element>ملاحظات الإرجاع</x-table.table-header-element>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($selectedItems as $id => $details)
                        <tr
                            class="{{ $annualRequest->items->where('id', $id)->first()?->pivot->objection_reason ? 'bg-red-100' : '' }}">
                            <x-table.data class="text-center w-1/5">{{ $details['name'] }}</x-table.data>
                            <x-table.data class="text-center w-1">{{ $details['unit'] }}</x-table.data>
                            <x-table.data >{{ $details['description'] }}</x-table.data>
                            <x-table.data class="w-1/6">
                                <x-text-input class="w-full" type="number" wire:model="selectedItems.{{ $id }}.quantity"
                                    min="0" step="1"/>
                            </x-table.data>
                            <x-table.data class="w-1 whitespace-nowrap">
                                <div class="flex justify-center">
                                    <x-danger-button wire:click="removeItem({{ $id }})">
                                        إزالة
                                    </x-danger-button>
                                </div>
                            </x-table.data>
                            @if ($annualRequest->return_reason)
                                <x-table.data>
                                    {{ $annualRequest->items->where('id', $id)->first()?->pivot->objection_reason}}
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
                <x-secondary-button wire:click="passRequest" class="flex-start bg-green-500">
                    إرسال الطلب
                </x-secondary-button>
            </div>
        </div>

    </section>
    @if ($annualRequest->return_reason)
        <div class="basis-1/3 flex flex-col mx-5 justify-center space-y-2 bg-red-100 p-5 border rounded-xl shadow-xl"
            style="position: sticky; top: 1rem; height: fit-content">
            <h1 class="text-xl font-bold text-center"> سبب الإرجاع </h1>
            <hr class="border-2 border-gray-500 rounded-lg" />
            <p>{{ $annualRequest->return_reason }}</p>
        </div>
    @endif
</div>

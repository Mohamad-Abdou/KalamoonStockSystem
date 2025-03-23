<section class="bg-white shadow-sm sm:rounded-lg basis-3/4">
    <div class="p-6 text-gray-900">
        <div class="">
            <div class="mb-4 flex flex-row w-full justify-between gap-4">
                <input wire:model.live="search" type="search" class="border px-4 py-2 rounded basis-1/3"
                    placeholder="البحث بالاسم والوصف" />
            </div>
            <x-table.table>
                <thead class="bg-gray-100 text-gray-700 text-center">
                    <tr>
                        <x-table.table-header-element>
                            اسم المادة
                        </x-table.table-header-element>
                        <x-table.table-header-element class='w-1'>
                            الوحدة
                        </x-table.table-header-element>
                        <x-table.table-header-element>
                            وصف المادة
                        </x-table.table-header-element>
                        <x-table.table-header-element class="w-fit">
                            المجموعة
                        </x-table.table-header-element>
                        <x-table.table-header-element class="w-1/6">
                            الكمية الاحتياطية
                        </x-table.table-header-element>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($list as $item)
                        <tr class="hover:bg-gray-50 transition duration-200 cursor-pointer"
                            wire:click="showItemDetails({{ $item->id }})">
                            <x-table.data>
                                {{ $item->item->name }}
                            </x-table.data>
                            <x-table.data>
                                {{ $item->item->unit }}
                            </x-table.data>
                            <x-table.data>
                                {{ $item->item->description }}
                            </x-table.data>
                            <x-table.data class="w-fit">
                                {{ $item->item->item_group->name }}
                            </x-table.data>
                            <x-table.data class="w-fit" onclick="event.stopPropagation()">
                                <x-text-input type="number"
                                    wire:model.live.debounce.500ms="quantities.{{ $item->id }}" class="w-full" />
                            </x-table.data>
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>
        </div>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl w-1/2 mx-4 relative">
                <h2 class="text-xl font-bold mb-4 text-right">تفاصيل المادة</h2>
                @if ($selectedItem)
                    <div class="space-y-4 text-right w-full">
                        <div class=" flex flex-row gap-3 w-full justify-around ">
                            <div class="grid grid-cols-2 gap-4 w-full border-l-4 border-gray-200">
                                <div>
                                    <h3 class="font-semibold">اسم المادة:</h3>
                                    <p>{{ $selectedItem->item->name }}</p>
                                </div>
                                <div>
                                    <h3 class="font-semibold">الوحدة:</h3>
                                    <p>{{ $selectedItem->item->unit }}</p>
                                </div>
                                <div>
                                    <h3 class="font-semibold">وصف المادة:</h3>
                                    <p>{{ $selectedItem->item->description }}</p>
                                </div>
                                <div>
                                    <h3 class="font-semibold">المجموعة:</h3>
                                    <p>{{ $selectedItem->item->item_group->name }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 w-full">
                                <div>
                                    <h3 class="font-semibold">الكمية الحالية في المستودع</h3>
                                    <p>{{ $selectedItem->inStockAvailble }}</p>
                                </div>
                                <div>
                                    <h3 class="font-semibold">الكمية المطلوبة للفصل الأول</h3>
                                    <p>{{ $selectedItem->first_semester_needed }}</p>
                                </div>
                                <div>
                                    <h3 class="font-semibold">الكمية المطلوبة للفصل الثاني</h3>
                                    <p>{{ $selectedItem->second_semester_needed }}</p>
                                </div>
                                <div>
                                    <h3 class="font-semibold">الكمية المطلوبة للفصل الثالث</h3>
                                    <p>{{ $selectedItem->third_semester_needed }}</p>
                                </div>
                            </div>
                        </div>


                        <div>
                            <div class="w-full flex justify-center">
                                <div class="w-1/3 mt-6">
                                    <h3 class="font-semibold mb-2">تعديل الكمية الاحتياطية:</h3>
                                    <div class="flex items-center">
                                        <x-text-input type="number"
                                            wire:model.live.debounce.500ms="quantities.{{ $selectedItem->id }}"
                                            class="w-full" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-danger-button wire:click="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white">
                            إغلاق
                        </x-danger-button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</section>

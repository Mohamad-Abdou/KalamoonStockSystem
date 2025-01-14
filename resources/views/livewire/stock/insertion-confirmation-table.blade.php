<section class="bg-white shadow-sm sm:rounded-lg w-full">
    <div class="p-6 text-gray-900">
        <x-table.table>
            <thead class="bg-gray-100  font-fit text-gray-700 text-center">
                <tr>
                    <x-table.table-header-element>
                        اسم المادة (وحدة)
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        وصف المادة
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        تاريخ الإدخال
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        تفاصيل العملية
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        الكمية
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                    </x-table.table-header-element>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($inStocks as $Stock)
                    <tr class="hover:bg-gray-50 transition duration-200 text-center">
                        <x-table.data>
                            {{ $Stock->item->name }} <br> ({{ $Stock->item->unit }})
                        </x-table.data>
                        <x-table.data>
                            {{ $Stock->item->description }}
                        </x-table.data>
                        <x-table.data class=" w-1/4">
                            {{ $Stock->created_at }}
                        </x-table.data>
                        <x-table.data class=" w-1/4">
                            {{ $Stock->details }}
                        </x-table.data>
                        <x-table.data>
                            {{ $Stock->in_quantity }}
                        </x-table.data>
                        <x-table.data class="w-1/6">
                            @if (!$Stock->approved)
                                <button wire:click="approve({{ $Stock->id }})"
                                    class=" bg-green-500 text-white px-4 py-2 rounded">توثيق</button>
                            @else
                                تم التوثيق
                            @endif
                        </x-table.data>
                    </tr>
                @endforeach
            </tbody>
        </x-table.table>
        <div class="mt-4">
            {{ $inStocks->links() }}
        </div>
    </div>
</section>

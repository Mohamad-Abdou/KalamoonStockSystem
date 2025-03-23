<div  class="flex flex-col w-3/4 justify-center items-center gap-3">
    @if(!$itemBalanceRemovedList->isEmpty())
        <h1 class=" text-2xl font-bold">تنبيه</h1>
        <p>تم اقتطاع كمية من رصيدك للمواد التالية</p>
        <x-table.table>
            <thead class="bg-gray-100 text-gray-700 text-center">
                <tr>
                    <x-table.table-header-element>
                        اسم المادة
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        الوحدة
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        وصف المادة
                    </x-table.table-header-element>
                    <x-table.table-header-element class="w-fit">
                        الكمية المقتطعة
                    </x-table.table-header-element>
                    <x-table.table-header-element class="w-1">
                        إمكانية الطلب
                    </x-table.table-header-element>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($itemBalanceRemovedList as $item)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <x-table.data>
                            {{ $item->item->name }}
                        </x-table.data>
                        <x-table.data>
                            {{ $item->item->unit }}
                        </x-table.data>
                        <x-table.data>
                            {{ $item->item->description }}
                        </x-table.data>
                        <x-table.data>
                            {{ $item->out_quantity }}
                        </x-table.data>
                        <x-table.data>
                            <x-primary-button wire:click="markAsSeen({{ $item->id }})" class="bg-green-500">
                                شوهد
                            </x-primary-button>
                        </x-table.data>
                    </tr>
                @endforeach
            </tbody>
        </x-table.table>
    @else
    <h1 class=" text-2xl font-bold">لا يوجد تنبيهات</h1>
        @endif
</div>
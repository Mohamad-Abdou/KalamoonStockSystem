<div class="w-full">
    <div class="mb-4 flex flex-row w-full justify-between gap-4">
        <div class="w-1/4">
            <input wire:model.live="searchByNameAndDetails" type="text" class="border px-4 py-2 rounded"
                placeholder="البحث بالاسم والوصف" />
        </div>
        @if ($filterOption === 'needed' && !$yearState)
            <div class="w-1/4 text-center text-red-400 font-semibold ">
                <p>
                    يرجى الانتباه إلى أن السنة غير فعالة والجهات لا زالت تقدم طلبات سنوية
                </p>
            </div>
        @endif
        <div class="flex items-center gap-4 w-1/4">
            <label class="inline-flex items-center">
                <input type="radio" wire:model.live="filterOption" value="all" class="form-radio text-primary">
                <span class="mr-2">الكل</span>
            </label>

            <label class="inline-flex items-center">
                <input type="radio" wire:model.live="filterOption" value="stock" class="form-radio text-primary">
                <span class="mr-2">جرد المستودع</span>
            </label>

            <label class="inline-flex items-center">
                <input type="radio" wire:model.live="filterOption" value="needed" class="form-radio text-primary">
                <span class="mr-2">مشتريات</span>
            </label>
        </div>
    </div>
    <x-table.table>
        <thead class="bg-gray-100 text-gray-700 text-center">
            <tr>
                <th colspan="11" class="px-4 py-2 border-2 border-gray-300 text-center bg-gray-100">
                    <div class="text-black text-center font-extrabold flex flex-col justify-center align-middle">
                        <label for="date"
                            class="block text-xl mb-2 font-extrabold w-full border-b-2 border-black">تاريخ بداية
                            السنة</label>
                        <p>{{ $lastReset->format('Y-m-d') }}</p>
                    </div>
                </th>
            </tr>
            <tr class=" text-center">
                <x-table.table-header-element rowspan='2'>
                    اسم المادة (الوحدة)
                </x-table.table-header-element>
                <x-table.table-header-element class="w-1/6 text-center" rowspan='2'>
                    وصف المادة
                </x-table.table-header-element>
                <x-table.table-header-element class="w-1/12" rowspan='2'>
                    الكمية الأساسية المدخلة للفصل الحالي
                </x-table.table-header-element>
                <x-table.table-header-element class="w-1/12" rowspan='2'>
                    الكمية الخارجة من بداية السنة
                </x-table.table-header-element>
                <x-table.table-header-element class="w-fit" rowspan='2'>
                    الكمية الحالية
                </x-table.table-header-element>
                <x-table.table-header-element class="w-fit" colspan='4'>
                    الكمية الكلية المطلوبة
                </x-table.table-header-element>
                <x-table.table-header-element class="w-fit" rowspan='2'>
                    فائض حر
                </x-table.table-header-element>
                <x-table.table-header-element class="w-1/12" rowspan='2'>
                    الكمية المطلوبة المتبقية لنهاية السنة
                </x-table.table-header-element>
            </tr>
            <tr>
                <x-table.table-header-element>الفصل الأول</x-table.table-header-element>
                <x-table.table-header-element>الفصل الثاني</x-table.table-header-element>
                <x-table.table-header-element>الفصل الصيفي</x-table.table-header-element>
                <x-table.table-header-element>الكلي</x-table.table-header-element>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($items as $item)
                <tr class="hover:bg-gray-50 transition duration-200 {{ $item->active ? '' : 'bg-red-100 font-bold' }}">
                    <x-table.data>
                        {{ $item->name }} ({{ $item->unit }})
                    </x-table.data>
                    <x-table.data>
                        {{ $item->description }}
                    </x-table.data>
                    <x-table.data>
                        {{ $item->mainInStock }}
                    </x-table.data>
                    <x-table.data>
                        {{ $item->totalOut }}
                    </x-table.data>
                    <x-table.data>
                        {{ $item->inStockAvalible }}
                    </x-table.data>
                    <x-table.data class="{{ $semester == 1 ? 'bg-green-100' : 'bg-red-100' }}">
                        {{ $item->firstSemesterNeeded }}
                    </x-table.data>
                    <x-table.data
                        class="{{ $semester == 2 ? 'bg-green-100' : '' }} {{ $semester == 3 ? 'bg-red-100' : '' }}">
                        {{ $item->secondSemesterNeeded }}
                    </x-table.data>
                    <x-table.data class="{{ $semester == 3 ? 'bg-green-100' : '' }}">
                        {{ $item->thirdSemesterNeeded }}
                    </x-table.data>
                    <x-table.data>
                        {{ $item->needed }}
                    </x-table.data>
                    <x-table.data>
                        {{ $item->extras }}
                    </x-table.data>
                    <x-table.data>
                        {{ $item->remainQuantity }}
                    </x-table.data>
                </tr>
            @endforeach
        </tbody>
    </x-table.table>
    <div class="flex flex-col justify-start mt-5">
        <div class="flex flex-row gap-2 justify-center items-center">
            <x-primary-button wire:click="exportToExcel" target="_blank">
                تصدير ملف excel
            </x-primary-button>
        </div>
    </div>
</div>

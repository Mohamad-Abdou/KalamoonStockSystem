<div wire class="felx flex-col items-center justify-center w-full space-y-4">
    <div class="flex flex-wrap gap-4 items-center justify-start ">
        <div class="flex flex-col gap-2">
            <label for="searchItem">بحث بالمادة</label>
            <x-text-input name="searchItem" wire:model.live.debounce.300ms="searchItem" type="search"
                placeholder="بحث باسم المادة  ..." />
        </div>
        <div class="flex flex-col gap-2">
            <label for="searchItemDetails">بحث بتفاصيل المادة</label>
            <x-text-input name="searchItemDetails" wire:model.live.debounce.300ms="searchItemDetails" type="search"
                placeholder="بحث بتفاصيل المادة ..." />
        </div>
        <div class="flex flex-col gap-2">
            <label for="searchDep">بحث بالجهة</label>
            <x-text-input name="searchDep" wire:model.live.debounce.300ms="searchDep" type="search"
                placeholder="بحث بالجهة ..." />
        </div>
        <div class="flex flex-col gap-2">
            <label for="searchDetails">بحث بتفاصيل العملية</label>
            <x-text-input name="searchDetails" wire:model.live.debounce.300ms="searchDetails" type="search"
                placeholder="بحث بتفاصيل العملية ..." />
        </div>
        <div class="flex gap-2">
            <div class="flex flex-col gap-2">
                <label for="fromDate">من تاريخ</label>
                <x-text-input name="fromDate" wire:model.live="filters.date_from" type="date" />
            </div>
            <div class="flex flex-col gap-2">
                <label for="ToDate">حتى تاريخ</label>
                <x-text-input name="ToDate" wire:model.live="filters.date_to" type="date" />
            </div>
        </div>
        <div class="flex flex-col justify-start">
            <div class="flex flex-row gap-2 justify-center items-center">
                <input name="thisYearCheckBox" type="checkbox"
                    class="toggle-checkbox text-primary border-gray-300 rounded" wire:click="toggleThisYear"
                    @checked($filters['this-year']) />
                <label for="thisYearCheckBox">السنة الحالية فقط</label>
            </div>
        </div>
    </div>
    <x-table.table>
        <thead class="bg-gray-100 text-gray-700 text-center">
            <tr>
                <x-table.table-header-element class="flex items-center gap-2 w-1 whitespace-nowrap hover:bg-gray-100">
                    <div wire:click="sortBy('created_at')">
                        التاريخ
                        @if ($sortField === 'created_at')
                            <span class="text-primary">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </div>
                </x-table.table-header-element>
                <x-table.table-header-element>
                    المادة
                </x-table.table-header-element>
                <x-table.table-header-element>
                    تفاصيل المادة
                </x-table.table-header-element>
                <x-table.table-header-element>
                    الجهة
                </x-table.table-header-element>
                <x-table.table-header-element class="w-1 whitespace-nowrap">
                    إدخال
                </x-table.table-header-element>
                <x-table.table-header-element class="w-1 whitespace-nowrap">
                    إخراج
                </x-table.table-header-element>
                <x-table.table-header-element>
                    التفاصيل
                </x-table.table-header-element>
                <x-table.table-header-element class="w-1 whitespace-nowrap">
                    الحالة
                </x-table.table-header-element>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($stocks as $stock)
                <tr>
                    <x-table.data
                        class="w-1 whitespace-nowrap">{{ $stock->created_at->format('Y-m-d (h:m) ') }}</x-table.data>
                    <x-table.data>{{ $stock->item->name }}</x-table.data>
                    <x-table.data class="w-1/4">{{ $stock->item->description }}</x-table.data>
                    <x-table.data>{{ $stock->user->role }}</x-table.data>
                    <x-table.data class="w-1 whitespace-nowrap">{{ $stock->in_quantity }}</x-table.data>
                    <x-table.data class="w-1 whitespace-nowrap">{{ $stock->out_quantity }}</x-table.data>
                    <x-table.data class="w-1/4">{{ $stock->details }}</x-table.data>
                    <x-table.data
                        class="w-1 whitespace-nowrap {{ $stock->approved ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <span>
                            {{ $stock->approved ? 'مدقق' : 'غير مدقق' }}
                        </span>
                    </x-table.data>
                </tr>
            @endforeach
        </tbody>
    </x-table.table>
    @if ($paginate)
        <div class="mt-4">
            {{ $stocks->onEachSide(1)->links() }}
        </div>
    @endif
    <x-primary-button wire:click="togglePagination">
        {{ $paginate ? 'عرض الكل للطباعة' : 'تفعيل الصفحات' }}
    </x-primary-button>
</div>

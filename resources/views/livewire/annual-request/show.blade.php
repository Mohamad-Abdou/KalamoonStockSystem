<div class="flex flex-col w-3/4" wire:loading.class="pointer-events-none opacity-100">
    @if ($request->state != 0 && $request->state != 2 && $holdWith)
        <div class="flex flex-col justify-center mb-7">
            <div class=" space-y-5">
                <h1 class="text-center font-bold"> الطلب قيد الدراسة لدى {{ $holdWith->role }}</h1>
                <p class="text-center font-bold">
                    الهاتف الداخلي : {{ $holdWith->office_number }}
                </p>
            </div>
        </div>
    @endif
    <x-table.table class="w-full">
        <thead class="bg-gray-100 text-gray-700 text-center">
            <tr>
                <x-table.table-header-element class="w-1/3" rowspan='2'>
                    المادة
                </x-table.table-header-element>
                <x-table.table-header-element rowspan='2'>
                    الوحدة
                </x-table.table-header-element>
                <x-table.table-header-element rowspan='2' class="w-1/3">
                    وصف المادة
                </x-table.table-header-element>
                <x-table.table-header-element colspan='4'> 
                    الكمية المطلوبة
                </x-table.table-header-element>
                @if ($request->state == -1 || $request->state == 2)
                    <x-table.table-header-element rowspan='2'> 
                        نقل إضافي
                    </x-table.table-header-element>
                @endif
                @if ($request->state == -1)
                    <x-table.table-header-element rowspan='2'>
                        الاستهلاك الكلي
                    </x-table.table-header-element>
                @endif
                @if ($request->state == 2)
                    <x-table.table-header-element rowspan='2'>
                        الرصيد المتبقي للفصل الحالي
                    </x-table.table-header-element>
                    @if (Auth::user()->type == 2)
                        <x-table.table-header-element rowspan='2'>
                            تجميد الرصيد
                        </x-table.table-header-element>
                    @endif
                @endif
            </tr>
            <tr>
                <x-table.table-header-element>الفصل الأول</x-table.table-header-element>
                <x-table.table-header-element>الفصل الثاني</x-table.table-header-element>
                <x-table.table-header-element>الفصل الصيفي</x-table.table-header-element>
                <x-table.table-header-element>الكلي</x-table.table-header-element>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($requestItems as $item)
                <tr class="text-center">
                    <x-table.data class="w-1/3">
                        {{ $item->name }}
                    </x-table.data>
                    <x-table.data>
                        {{ $item->unit }}
                    </x-table.data>
                    <x-table.data class="w-1/3">
                        {{ $item->description }}
                    </x-table.data>
                    
                    <x-table.data>
                        {{ $item->pivot->first_semester_quantity }}
                    </x-table.data>
                    <x-table.data>
                        {{ $item->pivot->second_semester_quantity }}
                    </x-table.data>
                    <x-table.data>
                        {{ $item->pivot->third_semester_quantity }}
                    </x-table.data>
                    <x-table.data>
                        {{ $item->pivot->quantity }}
                    </x-table.data>
                    
                    @if ($request->state == -1 || $request->state == 2)
                        <x-table.data>
                            {{ $item->extra_balance ?? 0 }}
                        </x-table.data>
                    @endif
                    @if ($request->state == -1)
                        <x-table.data>
                            {{ $item->consumed ?? 0 }}
                        </x-table.data>
                    @endif

                    @if ($request->state == 2)
                        <x-table.data class="{{ $item->balance <= 0 ? 'bg-red-500' : '' }}">
                            {{ $item->balance ?? 0 }}
                        </x-table.data>
                        @if (Auth::user()->type == 2)
                            <div wire:key="item-{{ $item->id }}">
                                <x-table.data class="text-center w-1">
                                    <input type="checkbox" class="toggle-checkbox text-primary border-gray-500 rounded"
                                        @cody i want to pass request id and item id
                                        wire:click="toggleFrozen({{ $request->id }}, {{ $item->id }})"
                                        @checked($item->pivot->frozen)>
                                </x-table.data>
                            </div>
                        @endif
                    @endif
                </tr>
            @endforeach
        </tbody>
    </x-table.table>
</div>

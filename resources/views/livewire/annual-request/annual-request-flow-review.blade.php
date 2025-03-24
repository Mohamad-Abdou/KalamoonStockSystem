<div class="flex flex-col justify-center items-center gap-10 px-8 py-2.5 max-y-full w-full">
    <section class="bg-white shadow-sm sm:rounded-lg  items-center">
        <div class="p-6 text-gray-900">
            <div>
                <x-table.table>
                    <thead class="bg-gray-100 text-gray-700 text-center">
                        <tr>
                            <x-table.table-header-element class="w-1/5" rowspan='2'>
                                اسم المادة
                            </x-table.table-header-element>
                            <x-table.table-header-element  rowspan='2'>
                                الوحدة
                            </x-table.table-header-element>
                            <x-table.table-header-element class="w-1/4" rowspan='2'>
                                وصف المادة
                            </x-table.table-header-element>
                            <x-table.table-header-element colspan='4'>
                                الكمية المطلوبة
                            </x-table.table-header-element>
                            @if ($previous_annual_request)
                                <x-table.table-header-element  rowspan='2'>
                                    الكمية المطلوبة في الطلب السابق
                                </x-table.table-header-element>
                                <x-table.table-header-element  rowspan='2'>
                                    الكمية المستهلكة من الطلب السابق
                                </x-table.table-header-element>
                            @endif
                            <x-table.table-header-element class="w-1/4"  rowspan='2'>
                                ملاحظات الإرجاع
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
                        @foreach ($annual_request->items as $item)
                            <tr
                                class="hover:bg-gray-50 transition duration-200 {{ $objection[$item->pivot->id] ? 'bg-red-100 font-bold' : '' }}">
                                <x-table.data class="w-1/5">
                                    {{ $item->name }}
                                </x-table.data>
                                <x-table.data class="w-1">
                                    {{ $item->unit }}
                                </x-table.data>
                                <x-table.data class="w-1/4">
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
                                @if ($previous_annual_request)
                                    <x-table.data>
                                        {{ $item->prev['quantity'] }}
                                    </x-table.data>
                                    <x-table.data>
                                        {{ $item->prev['consumed'] }}
                                    </x-table.data>
                                @endif
                                <x-table.data class="w-1/4">
                                    <x-text-input wire:model.live.debounce.1000ms="objection.{{ $item->pivot->id }}"
                                        class="w-full font-light" />
                                </x-table.data>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table.table>
            </div>
        </div>
    </section>
    <section class="w-1/3">
        <x-card header="القرار"
            class="bg-primary space-y-5 border-2 {{ !$annual_request->items->contains('pivot.objection_reason', true) ? 'border-green-500' : 'border-red-500' }}">
            <div class="flex justify-center ">
                @if (!$annual_request->items->contains('pivot.objection_reason', true))
                    <div class="space-y-4 flex flex-col items-center">
                        <button wire:click.once="passRequest"
                            class="bg-green-500 text-white p-2 rounded-md hover:bg-green-800">
                            تحويل الطلب
                        </button>
                        <h2 class="text-center text-white">يجب الاعتراض على مادة واحدة على الأقل لإرجاع الطلب</h2>
                    </div>
                @else
                    <div class="space-y-4">
                        <textarea wire:model="return_reason"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="سبب الإرجاع" required>{{ $return_reason }}</textarea>
                        @error('return_reason')
                            <div class="font-medium text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                        <button wire:click="rejectRequest"
                            class="bg-red-500 text-white p-2 rounded-md hover:bg-red-800">
                            إرجاع الطلب
                        </button>
                    </div>
                @endif
            </div>
        </x-card>
    </section>
</div>

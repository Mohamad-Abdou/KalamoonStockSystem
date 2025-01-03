<div class="flex justify-center gap-4 px-8 py-2.5 max-y-full w-full">
    <section class="bg-white shadow-sm sm:rounded-lg basis-3/4 items-center">
        <div class="p-6 text-gray-900">
            <div>
                <x-table.table>
                    <thead class="bg-gray-100 text-gray-700 text-center">
                        <tr>
                            <x-table.table-header-element class="w-1/5">
                                اسم المادة
                            </x-table.table-header-element>
                            <x-table.table-header-element class="w-1/4">
                                وصف المادة
                            </x-table.table-header-element>
                            <x-table.table-header-element>
                                الكمية المطلوبة
                            </x-table.table-header-element>
                            @if ($previous_annual_request)
                                <x-table.table-header-element>
                                    الكمية المطلوبة في الطلب السابق
                                </x-table.table-header-element>
                                <x-table.table-header-element>
                                    الكمية المستهلكة من الطلب السابق
                                </x-table.table-header-element>
                            @endif
                            <x-table.table-header-element class="w-1/4">
                                ملاحظات الإرجاع
                            </x-table.table-header-element>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($annual_request->items as $item)
                            <tr
                                class="hover:bg-gray-50 transition duration-200 {{ $objection[$item->pivot->id] ? 'bg-red-100 font-bold' : '' }}">
                                <x-table.data class="w-1/5">
                                    {{ $item->name }}
                                </x-table.data>
                                <x-table.data class="w-1/4">
                                    {{ $item->description }}
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
                                        class="w-full" />
                                </x-table.data>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table.table>
            </div>
        </div>
    </section>
    <section class="basis-1/4" style="position: sticky; top: 1rem; height: fit-content">
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

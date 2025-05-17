<div class="w-full flex flex-col justify-center items-center gap-8 h-full">
    <div class="basis-1/3 w-1/4">
        <!-- قسم لإضافة مجموعة -->
        <x-card header="إنشاء طلب جديد" class="bg-primary h-1/2 flex flex-col gap-4">
            <div class="relative">
                <x-text-input wire:model.live="search" wire:focus="$set('showDropdown', true)" type="text" class="w-full"
                    placeholder="البحث عن مادة..." />
                @error('selectedItem')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
                @if ($showDropdown)
                    <div class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg">
                        <ul class="py-1 max-h-60 overflow-y-auto">
                            @foreach ($this->filteredItems as $item)
                                <li>
                                    <button wire:click="selectItem({{ $item->id }})"
                                        class="w-full px-4 py-2 text-right hover:bg-gray-100">
                                        {{ $item->name }} ({{ $item->unit }}) الوصف: {{ $item->description }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            @if ($selectedItem)
                <div class="flex flex-col text-white text-center">
                    <h2 class="text-lg font-semibold">
                        {{ $selectedItem->name }} ({{ $selectedItem->unit }})
                    </h2>
                    <p>
                        الوصف: {{ $selectedItem->description }}
                    </p>
                </div>
            @endif

            <x-text-input wire:model.live="requestDetails.quantity" type="number" max="" placeholder="الكمية"
                class="w-full" />
            @error('requestDetails.quantity')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
            <x-text-input wire:model.live="requestDetails.reason" type='text' max="" placeholder="سبب الطلب"
                class="w-full" />
            @error('requestDetails.reason')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
            <div>
                <x-primary-button wire:click='submit' class="bg-green-500">
                    ارسال
                </x-primary-button>
            </div>
        </x-card>
    </div>
    <section class="bg-white shadow-xl sm:rounded-lg basis-2/3 w-full">
        <div class="flex flex-col mt-4">
            <h2 class="text-2xl font-bold text-center">
                الطلبات السابقة
            </h2>
        </div>
        <div class="p-6 text-gray-900">
            <x-table.table class="shadow-xl">
                <thead class="bg-gray-100 text-gray-700 text-center">
                    <tr>
                        <x-table.table-header-element>
                            تاريخ الطلب
                        </x-table.table-header-element>
                        <x-table.table-header-element>
                            اسم المادة
                        </x-table.table-header-element>
                        <x-table.table-header-element>
                            الوحدة
                        </x-table.table-header-element>
                        <x-table.table-header-element class="w-1">
                            الكمية المطلوبة
                        </x-table.table-header-element>
                        <x-table.table-header-element>
                            السبب
                        </x-table.table-header-element>
                        <x-table.table-header-element>
                            حالة الطلب
                        </x-table.table-header-element>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($UserRequests as $request)
                        <tr
                            class="hover:bg-gray-50 transition duration-200 {{ $request->state == 1 ? 'bg-orange-100' : ($request->state == 2 ? 'bg-green-100' : 'bg-red-100') }}">
                            <x-table.data class="w-1 text-nowrap" >
                                {{ $request->created_at }}
                            </x-table.data>
                            <x-table.data>
                                {{ $request->item->name }}
                            </x-table.data>
                            <x-table.data>
                                {{ $request->item->unit }}
                            </x-table.data>
                            <x-table.data>
                                {{ $request->quantity }}
                            </x-table.data>
                            @if (!$request->rejection_reason)
                                <x-table.data>
                                    {{ $request->reason }}
                                </x-table.data>
                            @else
                                <x-table.data>
                                    {{ $request->rejection_reason }}
                                </x-table.data>
                            @endif
                            <x-table.data class="w-1 text-nowrap">
                                {{ $request->state_text }}
                            </x-table.data>
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>
        </div>
    </section>
</div>

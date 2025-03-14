<div class="flex gap-4 w-full flex-col">
    <div class="flex items-center gap-4 border-b-2 border-gray-400 pb-4 justify-center">
        <x-text-input wire:model.live="search" type="search" placeholder="البحث عن مادة" />
        <div class="flex flex-col justify-start">
            <div class="flex flex-row gap-2 justify-center items-center">
                <input name="canRequest" type="checkbox" class="toggle-checkbox text-primary border-gray-300 rounded"
                    wire:model.live.="canRequest" @checked($canRequest) />
                <label for="canRequest">يمكن طلبها</label>
            </div>
        </div>
    </div>
    <div class="flex w-full flex-wrap">
        @foreach ($itemsToShow as $key => $item)
            @if (($canRequest && $item->balance > 0 && !$item->pivot->frozen) || !$canRequest)
                <div class="basis-1/5 p-4">
                    <x-card
                        class="flex flex-col h-full justify-between cursor-pointer transition-transform duration-200 {{ $item->pivot->frozen || $item->balance <= 0 ? 'border-red-500' : 'hover:scale-105 border-second-color' }} border-4  bg-primary max-w-1/3">
                        <div
                            wire:click="{{ !$item->pivot->frozen && !$item->balance <= 0 ? 'selectItem(' . $item->id . ')' : '' }}">
                            <div class="mb-2">
                                <x-slot:header class="mb-5 text-black">
                                    {{ $item->name }} ( {{ $item->unit }} )
                                </x-slot:header>
                            </div>
                            <p class="w-full text-gray-100 border-t-2 rounded border-white p-2">
                                {{ $item->description }}
                            </p>
                            <div class="flex flex-col w-full justify-between">
                                <p class="w-full text-gray-100 border-t-2 border-white pt-2">
                                    الكمية المطلوبة :
                                    @if ($currentSemester == 1)
                                        {{ $item->pivot->first_semester_quantity }}
                                    @elseif ($currentSemester == 2)
                                        {{ $item->pivot->second_semester_quantity }}
                                    @elseif ($currentSemester == 3)
                                        {{ $item->pivot->third_semester_quantity }}
                                    @endif
                                </p>
                                <p class="w-full text-gray-100 border-t-2 border-white pt-2">
                                    إضافي : {{ $item->extra_balance }}
                                </p>
                                <p class="w-full text-gray-100 border-t-2 border-white pt-2">
                                    الكمية المستهلكة : {{ $item->consumed }}
                                </p>
                                @if ($item->pivot->frozen)
                                    <p class="w-full text-red-500 border-t-2 text-center border-white pt-2">
                                        تم تجميد رصيد المادة من قبل أمانة الجامعة
                                    </p>
                                @elseif ($item->balance <= 0)
                                    <p class="w-full text-red-500 border-t-2 text-center border-white pt-2">
                                        لم يعد لهذه الجهة رصيد لها من هذه المادة
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-row justify-between w-full">
                            <a href="{{ route('periodic-request.show', $item->id) }}"
                                class="mt-4 bg-second-color text-black px-4 py-2 rounded">الأرشيف</a>
                        </div>
                    </x-card>
                </div>
            @endif
        @endforeach
    </div>
    @if ($showRequestModal)
        <div class="fixed inset-0 flex items-center justify-center z-40 bg-black bg-opacity-50">
            <div
                class="flex flex-col gap-3 justify-center items-center bg-white p-6 rounded shadow-lg w-1/3 border-second-color ">
                <div class="w-full">
                    <h2 class="text-lg font-semibold mb-4 text-center"> {{ $selectedItem->name }}
                        ({{ $selectedItem->unit }}) </h2>
                    <p>
                        {{ $selectedItem->description }}
                    </p>
                    <div class="flex flex-row border-t-2 gap-4 border-primary pt-4 justify-center">
                        @if ($allowedQuantity > 0)
                            <div class="flex flex-col justify-center">
                                <label for="quantity">الكمية المطلوبة</label>
                                <x-text-input wire:model.live="quantity" type="number" max="{{ $allowedQuantity }}"
                                    placeholder="القصوى : {{ $allowedQuantity }}" class="w-full" />
                                @error('quantity')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        @elseif (!$allowedQuantity >= 0)
                            <p class="text-red-500">لا يمكن طلب المادة حاليا</p>
                        @endif
                    </div>
                    <div class="flex justify-between w-full">
                        <button wire:click="closeModal"
                            class="mt-4 bg-red-500 text-white px-4 py-2 rounded">إغلاق</button>
                        @if ($allowedQuantity > 0)
                            <button wire:click="submitQuantity"
                                class="mt-4 bg-green-500 text-white px-4 py-2 rounded">طلب</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

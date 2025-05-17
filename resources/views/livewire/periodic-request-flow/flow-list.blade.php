<div class="flex gap-4 w-full flex-col">
    <div class="flex w-full flex-wrap justify-center">
        @if ($RequestsList->isNotEmpty())
        @foreach ($RequestsList as $key => $request)
        <div class="basis-1/5 p-4">
            <x-card
                class="z-5 flex flex-col h-full justify-between items-center cursor-pointer transition-transform duration-200 border-4 rounded-full border-second-color bg-primary max-w-1/3">
                <div wire:click="showDetails({{ $request->id }})" class="w-full">
                    <div class="border-b-2 border-second-color w-full p-2">
                        <x-slot:header class="mb-5 text-black">
                            {{ $request->user->role }}
                        </x-slot:header>
                    </div>
                    <div class="mb-2 border-b-2 border-second-color w-full text-gray-100 pb-4 pt-4">
                        <h1 class="text-center font-bold pb-4">
                            {{ $request->item->name }}
                            ({{ $request->item->unit }})
                        </h1>
                        <p>
                            {{ $request->item->description }}
                        </p>
                    </div>
                    <div class="text-gray-100">
                        <p>
                            الكمية المطلوبة : {{ $request->quantity }}
                        </p>
                    </div>
                </div>
                <div class="flex justify-between w-full">
                    <button wire:click="showRejectionModalButton({{ $request->id }})"
                        class="mt-4 bg-red-500 text-white px-4 py-2 rounded-full">رفض</button>
                    @can('update', $request)
                    <button wire:click="showEditModalButton({{ $request->id }})"
                        class="mt-4 bg-orange-500 text-white px-4 py-2 rounded-full">تعديل</button>
                    @endcan
                    <button wire:click="acceptRequest({{ $request->id }})"
                        class="mt-4 bg-green-500 text-white px-4 py-2 rounded-full">قبول</button>
                </div>
            </x-card>
        </div>
        @endforeach
        @endif

    </div>
    <div class="flex w-full flex-wrap justify-center">
        <div class="border-t-4 rounded-t-xl bg-gray-500 w-full p-4 shadow-sm shadow-black">
            <h2 class=" text-center text-xl font-bold text-white">
                طلبات غير مخطط لها
            </h2>
        </div>
        @if ($TemporaryRequestsList->isNotEmpty())
        <div class="flex w-full flex-wrap justify-center">
            @foreach ($TemporaryRequestsList as $key => $request)
            <div class="basis-1/5 p-4">
                <x-card
                    class="z-5 flex flex-col h-full justify-between items-center cursor-pointer transition-transform duration-200 border-4 rounded-full border-second-color bg-primary max-w-1/3">
                    <div class="w-full">
                        <div class="border-b-2 border-second-color w-full p-2">
                            <x-slot:header class="mb-5 text-black">
                                {{ $request->user->role }}
                            </x-slot:header>
                        </div>
                        <div class="mb-2 border-b-2 border-second-color w-full text-gray-100 pb-4 pt-4">
                            <h1 class="text-center font-bold pb-4">
                                {{ $request->item->name }}
                                ({{ $request->item->unit }})
                            </h1>
                            <p>
                                {{ $request->item->description }}
                            </p>
                        </div>
                        <div class="text-gray-100">
                            <p>
                                الكمية المطلوبة : {{ $request->quantity }}
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-between w-full">
                        <button wire:click="showTemporaryRejectionModalButton({{ $request->id }})"
                            class="mt-4 bg-red-500 text-white px-4 py-2 rounded-full">رفض</button>
                        <button wire:click="showTemporaryCheckModalButton({{ $request->id }})"
                            class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-full">معاينة</button>
                    </div>
                </x-card>
            </div>
            @endforeach
        </div>
        @endif
    </div>



    @if (!$RequestsList->isNotEmpty() && !$TemporaryRequestsList->isNotEmpty())
    <div class="flex flex-col w-full justify-center h-full items-center mt-16">
        <h1 class="text-center text-4xl">لا يوجد طلبات واردة</h1>
        <div class="w-1/3">
            <img src="/images/empty.gif" alt="empty" class="drop-shadow-md">
        </div>
    </div>
    @endif

    @if ($showDetailsModal)
    <div class="fixed inset-0 flex items-center justify-center z-40 bg-black bg-opacity-50">
        <div
            class="flex flex-col gap-3 justify-center items-center bg-white p-6 rounded shadow-lg w-1/3 border-second-color ">
            <div class="w-full">
                <div class="border-b-2 border-gray-500">
                    <h2 class="text-lg font-semibold mb-4 pb-4 text-center border-b-2 border-gray-500">
                        {{ $selectedItem->name }}
                        ({{ $selectedItem->unit }})
                    </h2>
                    <p class="pb-4 px-4">
                        {{ $selectedItem->description }}
                    </p>
                </div>
                <div class="flex flex-row justify-between">
                    <div class="text-center p-2">
                        <h3 class="font-bold">الكمية في المستودع</h3>
                        {{ $selectedItemDetails['stock'] }}
                    </div>
                    <div class="text-center p-2">
                        <h3 class="font-bold">استهلاك الجهة</h3>
                        {{ $selectedItemDetails['userConsumed'] }}
                    </div>
                    <div class="text-center p-2">
                        <h3 class="font-bold">الطلب السنوي للجهة</h3>
                        {{ $selectedItemDetails['userRequested'] }}
                    </div>
                </div>
                <div class="flex justify-between w-full align-middle">
                    <button wire:click="closeModal"
                        class="mt-4 bg-red-500 text-white px-4 py-2 rounded">إغلاق</button>
                    <p class="font-bold mt-5">
                        الكمية المطلوبة : {{ $selectedItemDetails['requestQuantity'] }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if ($showRejectionModal)
    <div class="fixed inset-0 flex items-center justify-center z-40 bg-black bg-opacity-50">
        <div
            class="flex flex-col gap-3 justify-center items-center bg-white p-6 rounded shadow-lg border-second-color ">
            <div class="w-full">
                <div>
                    <textarea wire:model="rejection_reason"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="سبب الرفض" required>{{ $rejection_reason }}</textarea>
                    @error('rejection_reason')
                    <div class="font-medium text-red-500">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="flex justify-between w-full align-middle">
                    <button wire:click="closeModal"
                        class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">إغلاق</button>
                    <button wire:click="rejectRequest"
                        class="mt-4 bg-red-500 text-white px-4 py-2 rounded">رفض</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if ($showEditModal)
    <div class="fixed inset-0 flex items-center justify-center z-40 bg-black bg-opacity-50">
        <div
            class="flex flex-col gap-3 justify-center items-center bg-white p-6 rounded shadow-lg border-second-color ">
            <div class="w-full ">
                <div>
                    <label for="quantity">الكمية</label>
                    <x-text-input wire:model.live="newQuantity" wire:keydown.enter="submitQuantity" min="0"
                        max="{{ $editRequest->quantity }}" type="number"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('newQuantity')
                    <div class="font-medium text-red-500">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="flex flex-row justify-between gap-4 w-full align-middle">
                    <button wire:click="closeModal"
                        class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">إغلاق</button>
                    <button wire:click="editAndPass" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">حفظ
                        وقبول</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($showTemporaryRejectionModal)
    <div class="fixed inset-0 flex items-center justify-center z-40 bg-black bg-opacity-50">
        <div class="flex flex-col gap-3 justify-center items-center bg-white p-6 rounded shadow-lg border-second-color">
            <div class="w-full">
                <div>
                    <textarea wire:model="temporaryRejection_reason"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="سبب الرفض" required>{{ $temporaryRejection_reason }}</textarea>
                    @error('temporaryRejection_reason')
                    <div class="font-medium text-red-500">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="flex justify-between w-full align-middle">
                    <button wire:click="closeModal"
                        class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">إغلاق</button>
                    <button wire:click="rejectTemporaryRequest"
                        class="mt-4 bg-red-500 text-white px-4 py-2 rounded">رفض</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($showTemporaryCheckModal)
    <div class="fixed inset-0 flex items-center justify-center z-40 bg-black bg-opacity-50">
        <div class="flex flex-col gap-3 justify-center items-center bg-white p-6 rounded shadow-lg w-1/3 border-second-color">
            <div class="w-full">
                <div class="border-b-2 border-gray-500">
                    <h2 class="text-lg font-semibold mb-4 pb-4 text-center border-b-2 border-gray-500">
                        {{ $temporarySelectedRequest->item->name }}
                        ({{ $temporarySelectedRequest->item->unit }})
                    </h2>
                    <p class="pb-4 px-4">
                        {{ $temporarySelectedRequest->item->description }}
                    </p>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-2 bg-gray-50 rounded">
                            <h3 class="font-bold">الكمية المطلوبة</h3>
                            {{ $temporarySelectedRequest->quantity }}
                        </div>
                        <div class="text-center p-2 bg-gray-50 rounded">
                            <h3 class="font-bold">الجهة</h3>
                            {{ $temporarySelectedRequest->user->role }}
                        </div>
                    </div>

                    <div class="border-t-2 border-gray-200 pt-4">
                        <h3 class="font-bold text-center mb-4">معلومات المخزون</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-2 bg-gray-50 rounded">
                                <h4 class="font-bold">الكمية المتوفرة</h4>
                                {{ $selectedItem->inStockAvailble ?? 0 }}
                            </div>
                            <div class="text-center p-2 bg-gray-50 rounded">
                                <h4 class="font-bold">الكمية الاحتياطية</h4>
                                {{ $selectedItem->quantity ?? 0 }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 border-t-2 border-gray-200 pt-4">
                        <h3 class="font-bold text-center mb-4">احتياجات الفصول</h3>
                        <div class="flex flex-row w-full items-center justify-center gap-4">
                            <div class="text-center p-2 bg-gray-50 rounded">
                                <h4 class="font-bold">الفصل الأول</h4>
                                {{ $selectedItem->first_semester_needed ?? 0 }}
                            </div>
                            <div class="text-center p-2 bg-gray-50 rounded">
                                <h4 class="font-bold">الفصل الثاني</h4>
                                {{ $selectedItem->second_semester_needed ?? 0 }}
                            </div>
                            <div class="text-center p-2 bg-gray-50 rounded">
                                <h4 class="font-bold">الفصل الثالث</h4>
                                {{ $selectedItem->third_semester_needed ?? 0 }}
                            </div>
                        </div>
                        <div class="flex flex-row w-full items-center justify-center gap-4">
                            <div class="text-center p-2 bg-gray-50 rounded">

                                <h4 class="font-bold">المتبقي لاتمام الفصل الحالي ({{
                                    ($currentSemester == 1? 'الفصل الأول' : ($currentSemester == 2 ?'الفصل الثاني':'الفصل الثالث'))
                                }} )</h4>
                                {{ $this->selectedItemDetails['NeededStockForSemester'] }}
                            </div>

                        </div>
                    </div>
                </div>
                <div class="flex justify-between w-full align-middle mt-6">
                    <div class="flex gap-2">
                        <button wire:click="closeModal"
                            class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">إغلاق</button>
                        <button wire:click="acceptTemporaryRequest"
                            class="mt-4 bg-green-500 text-white px-4 py-2 rounded">قبول</button>
                    </div>
                    <button wire:click="goToBalances"
                        class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">الذهاب إلى الأرصدة</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
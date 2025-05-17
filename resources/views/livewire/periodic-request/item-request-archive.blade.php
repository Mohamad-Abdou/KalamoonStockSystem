
    <x-slot:header>
        أرشيف طلبات الاحتياج لمادة {{ $item->name }} ({{ $item->unit }})
    </x-slot:header>
    <div class="flex flex-col w-1/2 ">
        @if ($requests->isNotEmpty())
            <x-table.table class="w-full">
                <thead class="bg-gray-100 text-gray-700 text-center">
                    <tr>
                        <x-table.table-header-element>
                            تاريخ الطلب
                        </x-table.table-header-element>
                        <x-table.table-header-element>
                            الكمية المطلوبة
                        </x-table.table-header-element>
                        <x-table.table-header-element>
                            حالة الطلب
                        </x-table.table-header-element>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($requests as $request)
                        <tr
                            class="text-center {{ $request->state === 0 ? 'bg-red-200' : ($request->state === -1 ? 'bg-green-200' : 'bg-orange-200') }} ">
                            <x-table.data>
                                {{ $request->created_at }}
                            </x-table.data>
                            <x-table.data>
                                {{ $request->quantity }}
                            </x-table.data>
                            <x-table.data>
                                {{ $request->request_state_text }}
                            </x-table.data>
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>
        @else
            <div class="flex flex-col w-full justify-center h-full items-center mt-16">
                <h1 class="text-center text-4xl">لم يتم طلب المادة بعد</h1>
                <div class="w-1/2">
                    <img src="/images/empty.gif" alt="empty" class="drop-shadow-md">
                </div>
            </div>
        @endif
    </div>

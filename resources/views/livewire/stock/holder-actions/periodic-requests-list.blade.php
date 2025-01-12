<section class="bg-white shadow-sm sm:rounded-lg w-full">
    <div class="p-6 text-gray-900">

        <x-table.table>
            <thead class="bg-gray-100  font-fit text-gray-700 text-center">
                <tr>
                    <x-table.table-header-element>
                        الجهة
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        <p class="text-xs">
                            الهاتف الداخلي للجهة
                        </p>
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        اسم المادة (وحدة)
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        وصف المادة
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        الكمية المطلوبة
                    </x-table.table-header-element>
                    <x-table.table-header-element>

                    </x-table.table-header-element>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($periodicRequests as $request)
                    <tr class="hover:bg-gray-50 transition duration-200 text-center">
                        <x-table.data>
                            {{ $request->user->role }}
                        </x-table.data>
                        <x-table.data class=" w-1/12">
                            {{ $request->user->office_number }}
                        </x-table.data>
                        <x-table.data class=" w-1/6">
                            {{ $request->item->name }} ({{ $request->item->unit }})
                        </x-table.data>
                        <x-table.data class=" w-1/4">
                            {{ $request->item->description }}
                        </x-table.data>
                        <x-table.data>
                            {{ $request->quantity }} ({{ $request->item->unit }})
                        </x-table.data>
                        <x-table.data class="w-1/6">
                            @if ($request->state == 2)
                                <button wire:click="applied({{ $request->id }})"
                                    class=" bg-green-500 text-white px-4 py-2 rounded">تسليم</button>
                            @else
                                <p>تم التسليم بتاريخ <br>
                                    {{ $request->updated_at->format('Y-m-d') }} <br>
                                    في الساعة : {{ $request->updated_at->format('H:i') }}
                                </p>
                            @endif
                        </x-table.data>
                    </tr>
                @endforeach
            </tbody>
        </x-table.table>
        <div class="mt-4">
            {{ $periodicRequests->links() }}
        </div>
    </div>
</section>

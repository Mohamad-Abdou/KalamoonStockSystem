<x-app-layout>
    <x-slot:header>
        الطلب السنوي لعام {{ $request->created_at->year }} شهر {{ $request->created_at->month }}
        ل{{ $request->user->role }}
    </x-slot:header>
    <div class="flex flex-col w-full">
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
                    <x-table.table-header-element class="w-1/6">
                        المادة
                    </x-table.table-header-element>
                    <x-table.table-header-element >
                        الوحدة
                    </x-table.table-header-element>
                    <x-table.table-header-element class="w-1/4">
                        وصف المادة
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        الكمية المطلوبة
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        نقل إضافي
                    </x-table.table-header-element>
                    <x-table.table-header-element>
                        الكمية المستهلكة الكلية
                    </x-table.table-header-element>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($requestItems as $item)
                    <tr class="text-center">
                        <x-table.data class="w-1/6">
                            {{ $item->name }}
                        </x-table.data>
                        <x-table.data>
                            {{ $item->unit }}
                        </x-table.data>
                        <x-table.data class="w-1/4">
                            {{ $item->description }}
                        </x-table.data>
                        <x-table.data>
                            {{ $item->pivot->quantity }}
                        </x-table.data>
                        <x-table.data>
                            {{ $item->quantity ?? 0 }}
                        </x-table.data>
                        <x-table.data>
                            {{ $item->quantity ?? 0 }}
                        </x-table.data>
                @endforeach
                </tr>
            </tbody>
        </x-table.table>
    </div>
</x-app-layout>

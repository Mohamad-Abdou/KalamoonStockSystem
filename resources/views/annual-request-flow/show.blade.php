<x-app-layout>
    <x-slot:header>
        {{ $annual_request->user->role }}
    </x-slot:header>
    <section class="bg-white shadow-sm sm:rounded-lg basis-2/3 items-center">
        <div class="p-6 text-gray-900">
            <div class="">
                <x-table.table>
                    <thead class="bg-gray-100 text-gray-700 text-center">
                        <tr>
                            <x-table.table-header-element>
                                اسم المادة
                            </x-table.table-header-element>
                            <x-table.table-header-element>
                                وصف المادة
                            </x-table.table-header-element>
                            <x-table.table-header-element>
                                الكمية المطلوبة
                            </x-table.table-header-element>
                            @if ($previous_annual_request)
                                <x-table.table-header-element>
                                    كمية الطلب السابق
                                </x-table.table-header-element>
                                <x-table.table-header-element>
                                    الكمية المستهلكة من الطلب السابق
                                </x-table.table-header-element>
                            @endif
                        </tr>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($annual_request->items as $item)
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <x-table.data>
                                    {{ $item->name }}
                                </x-table.data>
                                <x-table.data>
                                    {{ $item->description }}
                                </x-table.data>
                                <x-table.data>
                                    {{ $item->pivot->quantity }}
                                </x-table.data>
                                @if ($previous_annual_request)
                                    <x-table.data>
                                        {{ $item->prev['quantity'] }}
                                    </x-table.data>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </x-table.table>
            </div>
        </div>
    </section>
    </div>
</x-app-layout>

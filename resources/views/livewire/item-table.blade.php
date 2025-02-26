<div class="w-full">
    <div class="mb-4 flex flex-row w-full justify-between gap-4">

        <input wire:model.live="search" type="text" class="border px-4 py-2 rounded basis-1/2"
            placeholder="البحث بالاسم والوصف" />

        <x-input-dropdown-list wire:change="filterByGroup($event.target.value)" id="item_group_id"
            class="block basis-1/4" name="item_group_id" placeholder="">
            <option value="" disabled selected hidden>تصفية بالمجموعة</option>
            @foreach ($groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
        </x-input-dropdown-list>
        <div class="text-gray-700 font-semibold p-2 basis-1/4 text-center">
            عدد المواد: {{ $items->total() }}
        </div>
    </div>
    <x-table.table>
        <thead class="bg-gray-100 text-gray-700 text-center">
            <tr>
                <x-table.table-header-element>
                    اسم المادة
                </x-table.table-header-element>
                <x-table.table-header-element>
                    الوحدة
                </x-table.table-header-element>
                <x-table.table-header-element>
                    وصف المادة
                </x-table.table-header-element>
                <x-table.table-header-element class="w-fit">
                    المجموعة
                </x-table.table-header-element>
                <x-table.table-header-element class="w-1">
                    إمكانية الطلب
                </x-table.table-header-element>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($items as $item)
                <tr class="hover:bg-gray-50 transition duration-200 {{ $item->active ? '' : 'bg-red-100 font-bold' }}">
                    <x-table.data>
                        {{ $item->name }}
                    </x-table.data>
                    <x-table.data>
                        {{ $item->unit }}
                    </x-table.data>
                    <x-table.data>
                        {{ $item->description }}
                    </x-table.data>
                    
                    <x-table.data class="w-fit">
                        <x-input-dropdown-list class="border rounded w-fit"
                            wire:change="updateItemGroup({{ $item->id }}, $event.target.value)">
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" @if ($group->id === $item->item_group_id) selected @endif>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </x-input-dropdown-list>
                    </x-table.data>
                    <div wire:key="item-{{ $item->id }}">
                        <x-table.data class="text-center w-1" >
                            <input type="checkbox" class="toggle-checkbox text-primary border-gray-300 rounded"
                                wire:click="toggleState({{ $item->id }})" @checked($item->active)>
                        </x-table.data>
                    </div>
                </tr>
            @endforeach
        </tbody>
    </x-table.table>
    <div class="mt-4">
        {{ $items->onEachSide(1)->links() }}
    </div>
</div>

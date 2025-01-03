<div>
    <x-table.table>
        <thead class="bg-gray-100 text-gray-700 text-center">
            <tr>
                <x-table.table-header-element>
                    الجهة
                </x-table.table-header-element>
                @foreach ($groups as $group)
                    <x-table.table-header-element class="w-1">
                        {{ $group->name }}
                        <button 
                            type="button" 
                            wire:click="confirmDeleteGroup({{ $group->id }})"
                            class="text-red-500 hover:text-red-700">
                            &#x1F5D1;
                        </button>
                    </x-table.table-header-element>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($users as $user)
                <tr>
                    <x-table.data class="border">
                        {{ $user->role }}
                    </x-table.data>
                    @foreach ($groups as $group)
                        <x-table.data class="border px-4 py-2 text-center w-1">
                            <input type="checkbox" class="toggle-checkbox text-primary border-gray-300 rounded"
                                wire:click="toggleGroupAssociation({{ $user->id }}, {{ $group->id }})"
                                @if ($user->itemGroups && $user->itemGroups->contains($group->id)) checked @endif>
                        </x-table.data>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </x-table.table>
</div>
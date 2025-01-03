<x-card header="تدفق الطلبات الدورية" class="basis-1/3 justify-center bg-primary h-fit">
    <div class="bg-white w-full my-4 p-4 border rounded-md">
        <div class="mb-5">
            يمكن تغيير الترتيب بالسحب والإفلات
        </div>
        <x-table.table>
            <thead class="bg-gray-100 text-gray-700 text-center">
                <tr>
                    <x-table.table-header-element>الترتيب</x-table.table-header-element>
                    <x-table.table-header-element>المستخدم</x-table.table-header-element>
                    <x-table.table-header-element></x-table.table-header-element>

                </tr>
            </thead>
            <tbody x-sort="$wire.updateSort($item, $position)" class="divide-y divide-gray-200">
                @foreach ($TheFlow as $flow)
                <tr x-sort:item='{{ $flow->id }}' class="hover:bg-gray-50">
                        <x-table.data>
                            {{ $flow->order }}
                        </x-table.data>
                        <x-table.data>
                            {{ $flow->user->role }}
                        </x-table.data>
                        <x-table.data class="w-24 text-center">
                            <button wire:click="deleteFromFlow({{ $flow->id }})" class="text-red-600 hover:text-red-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-table.data>
                    </tr>
                @endforeach
            </tbody>
        </x-table.table>
        <div class="relative mt-5" x-data="{ open: false }" @click.away="open = false">
            <x-text-input 
                wire:model.live.debounce.300ms="search" 
                class="w-full"
                @focus="open=true"
                placeholder="إضافة مستخدم إلى التدفق" 
            />
            @if ($searchResults)
                <div x-show="open" class="absolute z-10 w-full bg-white mt-1 rounded-md shadow-lg">
                    @foreach ($searchResults as $user)
                        <div wire:click="addToFlow({{ $user->id }})"
                            class="p-2 hover:bg-gray-100 cursor-pointer">
                            {{ $user->role }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-card>

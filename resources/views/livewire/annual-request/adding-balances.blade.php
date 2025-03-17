<x-card header="صرف احتياطي" class="justify-center bg-primary h-fit w-full flex flex-col space-y-4">
    <p class="text-center text-white">
        يمكن صرف كمية من المادة دون طلب في حال وجود كمية احتياطية للمادة وكمية كافية من رصيد المادة في المستودع فقط
    </p>
    <div class="relative">
        <div class="flex flex-col space-y-2 mb-2">
            <x-text-input wire:model.live="searchUser" type="text" class="w-full" placeholder="الجهة المستلمة" />
        </div>
        @if ($UserSearchResault)
            <div class="fixed z-10 bg-white mt-1 rounded-md shadow-lg">
                @foreach ($this->UserSearchResault as $user)
                    <div wire:click="selectUser({{ $user->id }})" class="p-2 hover:bg-gray-100 cursor-pointer">
                        {{ $user->role }}
                    </div>
                @endforeach
            </div>
        @endif
        @if ($SelectedUser)
            <div class="flex flex-row justify-center tex-center text-white">
                نقل إلى : {{ $SelectedUser->role }}
            </div>

            <div class="flex flex-col space-y-2 mb-2">
                <x-text-input wire:model.live="itemsSearch" type="text" class="w-full" placeholder="المادة" />
            </div>
            @if ($ItemsSearchResault)
                <div class="fixed z-10 bg-white mt-1 rounded-md shadow-lg">
                    @foreach ($this->ItemsSearchResault as $item)
                        <div wire:click="selectItem({{ $item->id }})" class="p-2 hover:bg-gray-100 cursor-pointer">
                            {{ $item->name }} ({{ $item->unit }})
                        </div>
                    @endforeach
                </div>
            @endif
            @if ($selectedItem)
                <div class="flex flex-col space-y-2 text-center">
                    <p class="text-white">
                        المادة :
                        {{ $selectedItem->name }} ({{ $selectedItem->unit }})
                    </p>
                    <div class="flex flex-col mt-2">
                        <x-text-input wire:model.live="quantity" type="number" max="{{ $maxQuantity }}"
                            placeholder="المتاح : {{ $maxQuantity }} {{ $selectedItem->unit }}" />
                        @error('quantity')
                            <span class="text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
            @error('maxQuantity')
                <span class="text-red-500 mt-1">{{ $message }}</span>
            @enderror
        @endif
        @if ($showApplyButton)
        <button wire:click="submit"
            class="mt-4 bg-green-500 text-white px-4 py-2 rounded w-full">تنفيذ</button>
        @endif
    </div>
</x-card>

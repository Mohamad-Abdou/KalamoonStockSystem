<x-card header="نقل الأرصدة" class="justify-center bg-primary h-fit w-full flex flex-col space-y-4">
    <div class="flex flex-col space-y-2 justify-center">
        <div class="relative">
            <div class="flex flex-col space-y-2 mb-2">
                <x-text-input wire:model.live="searchFromUser" type="text" value="{{ $fromUser?->role }}" class="w-full"
                    placeholder="الجهة الأولى" />
            </div>
            @if ($showDropdownFromUser)
                <div class="fixed z-10 bg-white mt-1 rounded-md shadow-lg">
                    @foreach ($this->FromUserSearchResault as $user)
                        <div wire:click="selectFromUser({{ $user->id }})"
                            class="p-2 hover:bg-gray-100 cursor-pointer">
                            {{ $user->role }}
                        </div>
                    @endforeach
                </div>
            @endif
            @if ($fromUser)
                <div class="flex flex-row justify-center tex-center text-white">
                    نقل من : {{ $fromUser->role }}
                </div>
            @endif
        </div>
        @if ($fromUser)

            <div class="relative">
                <div class="flex flex-col space-y-2 mb-2">
                    <x-text-input wire:model.live="searchToUser" type="search" value="{{ $toUser?->role }}"
                        class="w-full" placeholder="الجهة الثانية" />
                </div>
                @if ($showDropdownToUser)
                    <div class=" fixed z-10 bg-white mt-1 rounded-md shadow-lg">
                        <ul class="py-1 max-h-60 overflow-y-auto">
                            @foreach ($this->ToUserSearchResault as $user)
                                <li>
                                    <button wire:click="selectToUser({{ $user->id }})"
                                        class="w-full px-4 py-2 text-right hover:bg-gray-100">
                                        {{ $user->role }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($toUser)
                    <div class="flex flex-row justify-center tex-center text-white">
                        إلى :{{ $toUser->role }}
                    </div>
                @endif
            </div>
        @endif
        @if ($items)
            <div class="relative">
                <x-text-input wire:model.live="searchItem" type="search" class="w-full" placeholder="المادة" />
                @if ($SearchItemsResault)
                    <div class="fixed z-100  mt-1 bg-white rounded-md shadow-lg">
                        <ul class="py-1 max-h-60 overflow-y-auto">
                            @foreach ($this->SearchItemsResault as $item)
                                <li>
                                    <button wire:click="selectItem({{ $item->id }})"
                                        class="w-full px-4 py-2 text-right hover:bg-gray-100">
                                        {{ $item->name }} ({{ $item->unit }})
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
        @if ($showApplyButton)
            <button wire:click="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">تنفيذ</button>
        @endif
    </div>
</x-card>

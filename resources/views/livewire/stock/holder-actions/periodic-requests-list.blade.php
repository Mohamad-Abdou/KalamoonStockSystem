<section class="bg-white shadow-sm sm:rounded-lg w-full">
    <div class="p-6 text-gray-900">
        <div class="flex flex-row justify-between items-center mb-4">
            <div class="flex flex-row items-center gap-2 w-1/4">
                <label class="inline-flex items-center">
                    <input type="radio" wire:model.live="filterOption" value="0" class="form-radio text-primary">
                    <span class="mr-2">الطلبات الدورية</span>
                </label>

                <label class="inline-flex items-center">
                    <input type="radio" wire:model.live="filterOption" value="1" class="form-radio text-primary">
                    <span class="mr-2">الطلبات الغير مخطط لها</span>
                </label>
            </div>
            <div class="">
                <x-input-dropdown-list wire:change="filterByUser($event.target.value)" id="user_id"
                    class="block basis-1/4" name="user_id" placeholder="">
                    <option value="" disabled selected hidden>تصفية بالمستخدم</option>
                    <option value="0">الكل</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->role }}</option>
                    @endforeach
                </x-input-dropdown-list>
            </div>
            <div class="flex items-center gap-2">
                <input name="WaitingRequestsCheckBox" type="checkbox"
                    class="toggle-checkbox text-primary border-gray-300 rounded" wire:click="toggleWaitingRequests"
                    @checked($WaitingRequests) />
                <label for="WaitingRequestsCheckBox">الغير مسلمة فقط</label>
            </div>         
        </div>
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
                    @foreach ($listToShow as $request)
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
                {{ $listToShow->links() }}
            </div>
        </div>
</section>

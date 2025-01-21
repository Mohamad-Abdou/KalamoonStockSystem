<div class="w-full">
    <div class="mb-4 flex flex-row w-full justify-between gap-4">
        <input wire:model.live="searchByRole" type="text" class="border px-4 py-2 rounded w-1/4"
            placeholder="البحث عن جهة" />
    </div>
    <x-table.table>
        <thead class="bg-gray-100 text-gray-700 text-center">
            <tr>
                <x-table.table-header-element>
                    اسم المستخدم
                </x-table.table-header-element>
                <x-table.table-header-element>
                    الجهة
                </x-table.table-header-element>
                <x-table.table-header-element>
                    الهاتف الداخلي
                </x-table.table-header-element>
                <x-table.table-header-element class="w-fit">
                    نوع المستخدم
                </x-table.table-header-element>
                <x-table.table-header-element class="w-1">
                    الحالة
                </x-table.table-header-element>
                <x-table.table-header-element class="w-1">
                    عمليات
                </x-table.table-header-element>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-center">
            @foreach ($users as $user)
                <tr class="hover:bg-gray-50 transition duration-200 {{ $user->active ? '' : 'bg-red-100 font-bold' }}">
                    <x-table.data>
                        {{ $user->name }}
                    </x-table.data>
                    <x-table.data>
                        {{ $user->role }}
                    </x-table.data>
                    <x-table.data>
                        {{ $user->office_number }}
                    </x-table.data>
                    <x-table.data class="w-fit">
                        {{ $user->user_type_text }}
                    </x-table.data>
                    <x-table.data class="w-fit">
                        {{ $user->active ? 'مفعل' : 'غير مفعل' }}
                    </x-table.data>
                    <x-table.data class="w-fit">
                        <div class="flex gap-2 justify-center">
                            <button wire:click="editUser({{ $user->id }})"
                                class="bg-orange-500 text-white font-bold px-4 py-2 rounded hover:bg-orange-700">
                                تعديل
                            </button>
                        </div>
                    </x-table.data>
                </tr>
            @endforeach
            <tr class="hover:bg-gray-50 transition duration-200">
                <x-table.data>
                    <input wire:model="newUser.name" type="text" class="border px-4 py-2 rounded w-full"
                        placeholder="اسم المستخدم">
                    @error('newUser.name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </x-table.data>
                <x-table.data>
                    <input wire:model="newUser.role" type="text" class="border px-4 py-2 rounded w-full"
                        placeholder="الجهة">
                    @error('newUser.role')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </x-table.data>
                <x-table.data>
                    <input wire:model="newUser.office_number" type="text" class="border px-4 py-2 rounded w-full"
                        placeholder="الهاتف الداخلي">
                    @error('newUser.office_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </x-table.data>
                <x-table.data>
                    <p>مستخدم</p>
                </x-table.data>
                <x-table.data>
                    <p>فعال</p>
                </x-table.data>
                <x-table.data>
                    <button wire:click="saveNewUser" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        إضافة
                    </button>
                </x-table.data>
            </tr>
        </tbody>
    </x-table.table>
    <div class="mt-4">
        {{ $users->onEachSide(1)->links() }}
    </div>

    @if ($isEditModalOpen)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-1/3">
                <h2 class="text-lg font-bold mb-4">تعديل المستخدم</h2>

                <div class="mb-4">
                    <label class="block mb-2">اسم المستخدم</label>
                    <input wire:model="editingUser.name" type="text" class="border px-4 py-2 rounded w-full">
                    @error('editingUser.name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2">الهاتف الداخلي</label>
                    <input wire:model="editingUser.office_number" type="text"
                        class="border px-4 py-2 rounded w-full">
                    @error('editingUser.office_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <button wire:click="updateUser" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        حفظ
                    </button>
                    <button wire:click="closeEditModal"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

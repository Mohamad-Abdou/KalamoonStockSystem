<x-app-layout>
    <!-- العمود الأيمن-->
    <!-- قسم عرض المواد وتعديلها -->
    <section class="bg-white shadow-sm sm:rounded-lg basis-2/3">
        <div class="p-6 text-gray-900">
            <div class="">
                @livewire('item-table', ['groups' => $groups])
            </div>
        </div>
    </section>
    <!-- العمود الأيسر-->
    <div class="basis-1/3">
        <!-- قسم لإضافة مجموعة -->
        <x-card header="إضافة مادة جديدة">
            <form method="POST" action="{{ route('items.store') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="name" :value="__('اسم المادة')" class="text-white" />
                    <x-text-input id="name" class="block mt-3 w-full" type="text" name="name"
                        :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="unit" :value="__('الوحدة')" class="text-white" />
                    <x-text-input id="unit" class="block mt-3 w-full" type="text" name="unit"
                        :value="old('unit')" required />
                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="description" :value="__('وصف المادة')" class="text-white mb-2" />
                    <x-text-input id="description" class="block w-full" type="text" name="description"
                        :value="old('description')" required />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="item_group_id" :value="__('المجموعة')" class="text-white mb-2" />
                    <x-input-dropdown-list id="item_group_id" class="block w-full" name="item_group_id" placeholder="">
                        <option value="" disabled selected hidden>اختر المجموعة</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </x-input-dropdown-list>
                    <x-input-error :messages="$errors->get('item_group_id')" class="mt-2" />
                </div>
                <x-secondary-button type="submit" class="mt-2 bg-second-color text-black">
                    {{ __('إضافة') }}
                </x-secondary-button>
            </form>
        </x-card>
    </div>
</x-app-layout>

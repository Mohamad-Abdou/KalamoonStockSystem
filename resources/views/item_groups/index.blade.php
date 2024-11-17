<x-app-layout>
    <!-- العمود الأيمن-->
    <!-- قسم عرض المواد وتعديلها -->
    <section class="bg-white shadow-sm sm:rounded-lg basis-2/3">
        <div class="p-6 text-gray-900">
            <div class="">
                @livewire('user-group-table')
            </div>
        </div>
    </section>
    <!-- العمود الأيسر-->
    <div class="basis-1/3">
        <!-- قسم لإضافة مجموعة -->
        <x-card header="إضافة مجموعة جديدة">
            <form method="POST" action="{{ route('item_groups.store') }}">
                @csrf
                <div>
                    <x-input-label for="group_name" class="text-white" :value="__('اسم المجموعة')" />
                    <x-text-input id="group_name" class="block mt-3 w-full" type="text" name="name"
                        :value="old('group_name')" required />
                    <x-input-error :messages="$errors->get('group_name')" class="mt-2" />
                </div>

                <x-secondary-button type="submit" class="mt-2 bg-second-color text-black">
                    {{ __('إضافة') }}
                </x-secondary-button>
            </form>
        </x-card>
    </div>
</x-app-layout>

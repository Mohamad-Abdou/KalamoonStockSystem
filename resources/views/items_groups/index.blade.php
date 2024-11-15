<x-app-layout>
    <div class="py-6">
        <div class="flex justify-between gap-4 px-8 py-2.5 max-y-full">
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
                <section class="bg-primary overflow-hidden shadow-xl sm:rounded-lg p-5">
                    <h3 class="text-center text-white font-semibold">إضافة مجموعة جديدة</h3>
                    <form method="POST" action="{{ route('items_groups.store') }}">
                        @csrf
                        <div>
                            <x-input-label for="goure_name" class="text-white" :value="__('اسم المجموعة')" />
                            <x-text-input id="group_name" class="block mt-3 w-full" type="text" name="name"
                                :value="old('group_name')" required />
                            <x-input-error :messages="$errors->get('group_name')" class="mt-2" />
                        </div>
                        
                        <x-secondary-button type="submit" class="mt-2 bg-second-color text-black">
                            {{ __('إضافة') }}
                        </x-secondary-button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
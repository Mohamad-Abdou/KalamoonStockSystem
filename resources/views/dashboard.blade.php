<x-app-layout>
    <div class="py-12 w-full">
        <div class="flex flex-col gap-10 w-full max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
            <div class="w-full">
                <section class="bg-white shadow-sm sm:rounded-lg basis-2/3">
                    <div class="p-6 text-gray-900 flex flex-col justify-center items-center">
                            @livewire('NotifyList')
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>

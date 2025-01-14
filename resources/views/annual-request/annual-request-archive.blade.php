<div class="flex gap-4 w-full flex-col space-y-4">
    <div class="flex items-center gap-4 border-b-2 border-gray-400 py-2">
        <div class="">
            <div class="flex items-center gap-2">
                <input type="radio" wire:model.live="filterState" value="all" name="requestState"
                    class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <label>جميع الطلبات</label>
            </div>
            <div class="flex items-center gap-2">
                <input type="radio" wire:model.live="filterState" value="2" name="requestState"
                    class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <label>في المستودع</label>
            </div>
            <div class="flex items-center gap-2">
                <input type="radio" wire:model.live="filterState" value="-1" name="requestState"
                    class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <label>في الأرشيف</label>
            </div>
            <div class="flex items-center gap-2">
                <input type="radio" wire:model.live="filterState" value="study" name="requestState"
                    class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <label>قيد الدراسة</label>
            </div>
        </div>
        <div class="flex flex-col gap-2">

            <div class="flex items-center gap-2">
                <span>بين</span>
                <input type="date" wire:model.live="dateFrom"
                    class="rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <span>و</span>
                <input type="date" wire:model.live="dateTo"
                    class="rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <input type="text" wire:model.live="search" placeholder="البحث باسم المستخدم"
                class="rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="flex justify-end">
            <button wire:click="resetFilters" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                إعادة التعيين
            </button>
        </div>
    </div>

    <div class="flex w-full flex-wrap ">
        @foreach ($annual_requests as $request)
            <div class="basis-1/5 p-4">
                <a href="{{ route('annual-request.show', $request) }}">
                    <x-card
                        class="flex flex-col items-center {{ $request->state === -1 ? 'bg-gray-500' : ($request->state === 2 ? 'bg-green-500' : 'bg-second-color') }}">
                        <x-slot:header>
                            {{ $request->user->role }}
                        </x-slot:header>
                        <div class="text-center">
                            {{ $request->getRequestStateTextAttribute() }}
                            <br>
                            {{ $request->created_at->format('m-Y') }}
                        </div>
                    </x-card>
                </a>
            </div>
        @endforeach
    </div>
    <div class="mt-4">
        {{ $annual_requests->links() }}
    </div>
</div>

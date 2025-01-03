<x-app-layout>
    <x-card header="فترة الطلب السنوي القادم" class="basis-1/3 justify-center bg-primary h-fit">
        <form action="{{ route('admin.annual-requests.update-period') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="flex flex-col justify-center">
                <x-input-label for="request_start_date" :value="__('بداية الفترة')" class="text-white mb-2 rounded" />
                <input type="date" id="request_start_date" name="request_start_date"
                    value="{{ old('request_start_date', $startDate->format('Y-m-d')) }}" required min="{{ date('Y-m-d') }}"
                    class="@error('request_start_date') border-red-500 @enderror">
                @error('request_start_date')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col justify-center mt-4">
                <x-input-label for="request_end_date" :value="__('نهاية الفترة')" class="text-white mb-2" />
                <input type="date" id="request_end_date" name="request_end_date"
                    value="{{ old('request_end_date', $endDate->format('Y-m-d')) }}" required min="{{ date('Y-m-d') }}"
                    class="@error('request_end_date') border-red-500 @enderror">
                @error('request_end_date')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            <x-secondary-button type="submit" class="mt-2 bg-second-color text-black">
                {{ __('حفظ') }}
            </x-secondary-button>
        </form>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </x-card>
    @livewire('ConfigAnnualRequestFlow')
</x-app-layout>

<x-app-layout>
    <x-card header="موعد الطلبات السنوية">
        <form action="{{ route('admin.annual-requests.update-period') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Display current request period values -->
            <div>
                <x-input-label for="request_start_date" :value="__('بداية الفترة')" class="text-white mb-2" />
                <input type="date" id="request_start_date" name="request_start_date"
                    value="{{ old('request_start_date', $startDate) }}" required>
            </div>

            <div>
                <x-input-label for="request_end_date" :value="__('نهاية الفترة')" class="text-white mb-2" />
                <input type="date" id="request_end_date" name="request_end_date"
                    value="{{ old('request_end_date', $endDate) }}" required>
            </div>
            <x-secondary-button type="submit" class="mt-2 bg-second-color text-black">
                {{ __('حفظ') }}
            </x-secondary-button>
        </form>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </x-card>
</x-app-layout>

<x-app-layout>
    <x-slot:header>
        {{ $annual_request->user->role }} \ هاتف داخلي : {{ $annual_request->user->office_number }}
    </x-slot:header>
    @livewire('AnnualRequest.AnnualRequestFlowReview', ['annual_request' => $annual_request, 'previous_annual_request' => $previous_annual_request])
</x-app-layout>

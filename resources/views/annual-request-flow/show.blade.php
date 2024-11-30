<x-app-layout>
    <x-slot:header>
        {{ $annual_request->user->role }}
    </x-slot:header>
    @livewire('annual_request_flow_review', ['annual_request' => $annual_request, 'previous_annual_request' => $previous_annual_request])
</x-app-layout>

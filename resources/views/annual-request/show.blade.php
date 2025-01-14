<x-app-layout>
    <x-slot:header>
        الطلب السنوي لعام {{ $request->created_at->year }} شهر {{ $request->created_at->month }}
        ل{{ $request->user->role }}
    </x-slot:header>
    @livewire('AnnualRequest.show', ['requestItems' => $requestItems, 'holdWith' => $holdWith, 'request' => $request])
</x-app-layout>

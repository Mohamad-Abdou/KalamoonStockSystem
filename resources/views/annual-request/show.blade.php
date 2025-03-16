<x-app-layout>
    <x-slot:header>
        الطلب السنوي لعام {{ $request->created_at->year }} / {{ $request->created_at->year + 1 }}
        ل{{ $request->user->role }}
    </x-slot:header>
    @livewire('AnnualRequest.show', ['requestItems' => $requestItems, 'holdWith' => $holdWith, 'request' => $request])
</x-app-layout>

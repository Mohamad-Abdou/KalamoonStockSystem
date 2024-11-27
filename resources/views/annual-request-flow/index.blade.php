<x-app-layout>
    <div class="flex flex-row flex-wrap w-full">
        @foreach ($incoming_requests as $annual_request)
            <a href="{{ route('annual-request-flow.show', $annual_request) }}" class="w-1/4 p-2">
                <x-card class="bg-primary border-8  space-y-5 {{ !$annual_request->return_reason? 'border-green-300':'border-red-400' }}">
                    <x-slot:header>
                        {{ $annual_request->user->role }}
                    </x-slot:header>
                    <h3 class="text-center text-white font-bold">
                        {{ !$annual_request->return_reason? 'طلب جديد':'مرتجع' }}
                    </h3>
                </x-card>
            </a>
        @endforeach
    </div>
</x-app-layout>

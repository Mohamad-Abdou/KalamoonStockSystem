<x-app-layout>
    @if ($incoming_requests->count())
        <div class="flex flex-row flex-wrap w-full">
            @foreach ($incoming_requests as $annual_request)
                <a href="{{ route('annual-request-flow.show', $annual_request) }}" class="w-1/4 p-2">
                    <x-card
                        class="bg-primary border-8  space-y-5 {{ !$annual_request->return_reason ? 'border-green-300' : 'border-red-400' }}">
                        <x-slot:header>
                            {{ $annual_request->user->role }}
                        </x-slot:header>
                        <h3 class="text-center text-white font-bold">
                            {{ !$annual_request->return_reason ? 'طلب جديد' : 'مرتجع' }}
                        </h3>
                    </x-card>
                </a>
            @endforeach
        </div>
    @else
        <div class="flex flex-col w-full justify-center h-full items-center mt-16">
            <h1 class="text-center text-4xl">لا يوجد طلبات واردة</h1>
            <div class="w-1/3">
                <img src="/images/empty.gif" alt="empty" class="drop-shadow-md">
            </div>
        </div>
    @endif
</x-app-layout>

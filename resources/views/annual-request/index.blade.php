<x-app-layout>
    <div class="w-full justify-center">
        @if (App\Models\AnnualRequest::isActiveRequestPeriod())
            @can('create', App\Models\AnnualRequest::class)
                <div class="mb-5 flex justify-center">
                    <h1>تنتهي فترة التسجيل على الطلب الاحتياج السنوي في {{ $periodEndAt->format('Y-m-d') }} </h1>
                    <a href={{ route('annual-request.create') }} class="font-bold text-red-500 px-2"> عرض الطلب </a>
                </div>
            @endcan
        @endif
        <div class="flex justify-center flex-wrap">
            @foreach ($requests as $request)
                <a href="{{ route('annual-request.show', $request) }}" class="basis-1/5 mx-3 mb-3">
                    <x-card
                        class=" {{ $request->state === 0
                            ? ($request->return_reason
                                ? 'bg-red-600' // الطلب مرتجع
                                : 'bg-blue-600') // الطلب مسودة
                            : ($request->state === 2
                                ? 'bg-green-600' // الطلب فعال حالياً
                                : ($request->state === -1
                                    ? 'bg-gray-600' // الطلب أرشيف
                                    : 'bg-yellow-600')) }} // الطلب قيد الدراسة 
                                text-center"
                        header='{{ $request->created_at->year }} - {{ $request->created_at->month }}'>
                        <h2 class="font-bold">{{ $request->RequestStateText }}</h2>
                    </x-card>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>

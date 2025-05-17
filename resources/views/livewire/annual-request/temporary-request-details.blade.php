<div>
    @if($temporaryRequest)
    <div class="basis-1/4">
        <div class="bg-white rounded-lg shadow-lg p-6 border-4 border-second-color">
            <div class="border-b-2 border-second-color w-full p-2 mb-4">
                <h2 class="text-xl font-bold text-center">تفاصيل الطلب الغير مخطط</h2>
            </div>
            <div class="space-y-4">
                <div class="text-center p-2 bg-gray-50 rounded">
                    <h3 class="font-bold">الجهة</h3>
                    <p class="text-gray-700">{{ $temporaryRequest->user->role ?? 'غير محدد' }}</p>
                </div>
                <div class="text-center p-2 bg-gray-50 rounded">
                    <h3 class="font-bold">الصنف</h3>
                    <p class="text-gray-700">{{ $temporaryRequest->item->name ?? 'غير محدد' }}</p>
                </div>
                <div class="text-center p-2 bg-gray-50 rounded">
                    <h3 class="font-bold">الكمية المطلوبة</h3>
                    <p class="text-gray-700">{{ $temporaryRequest->quantity ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    @endif
</div>
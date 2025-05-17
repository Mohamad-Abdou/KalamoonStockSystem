<x-app-layout>
    @if ($activeYear)
    <div class="flex flex-row w-full justify-around m-8 px-10">
        <div class="basis-1/4">
            @livewire('AnnualRequest.MovingBalances')
        </div>


        @livewire('AnnualRequest.TemporaryRequestDetails', ['requestId' => request('request')])


        <div class="basis-1/4">
            @livewire('AnnualRequest.AddingBalances')
        </div>
    </div>
    @else
    <div>
        لا يمكن إدارة الأرصدة قبل تفعيل السنة
    </div>
    @endif
</x-app-layout>
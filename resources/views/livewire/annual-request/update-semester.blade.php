<x-card header="الفصل الحالي" class="basis-1/3 justify-center bg-second-color h-fit">
    <div class="flex justify-between items-center bg-white w-full my-4 p-4 border rounded-md">
        <div>
            @if ($currentSemester == 1)
                الفصل الدراسي الأول
            @elseif ($currentSemester == 2)
                الفصل الدراسي الثاني
            @else
                الفصل الدراسي الصيفي
            @endif
        </div>
        @if ($currentSemester != 3)
            <div>
                <x-primary-button wire:click='updateSemester' class="bg-primary text-black">
                    بدء الفصل التالي
                </x-primary-button>
            </div>
        @endif
    </div>
</x-card>

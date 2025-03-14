<x-app-layout>
    <x-slot:header>
        @if ($currentSemester == 1)
            الفصل الدراسي الأول
        @elseif ($currentSemester == 2)
            الفصل الدراسي الثاني
        @else
            الفصل الدراسي الصيفي
        @endif
    </x-slot:header>
    @livewire('PeriodicRequest.ItemRequest')
</x-app-layout>
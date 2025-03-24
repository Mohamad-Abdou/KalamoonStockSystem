<?php

namespace App\Livewire\AnnualRequest;

use App\Models\AnnualRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

use function Pest\Laravel\get;

class AnnualRequestCreate extends Component
{
    use WithPagination;
    public $search = '';
    public $showDropdown = false;
    public $itemsToRequest;
    public $selectedItems = [];
    public $isProcessing = false;
    public $filterdItems;

    public function mount()
    {
        $user = Auth::user();
        // اختيار المواد التي يمكن للمستخدم طلبها فقط
        $this->itemsToRequest = $user->items();
        $this->filterdItems = $this->itemsToRequest;
    }

    public function updatedSearch()
    {
        $this->showDropdown = true;
    }

    public function getFilteredItemsProperty()
    {
        if (!$this->search) {
            return $this->itemsToRequest;
        }
        return $this->itemsToRequest
            ->filter(fn($item) => str_contains(strtolower($item->name), strtolower($this->search)));
    }

    public function updatedSelectedItems($value, $key)
    {
        $matches = [];
        preg_match('/(\d+)\.(first|second|third)_semester_quantity/', $key, $matches);


        if (count($matches) === 3) {
            $itemId = $matches[1];
            $this->selectedItems[$itemId]['total_quantity'] =
                (int)($this->selectedItems[$itemId]['first_semester_quantity'] ?? 0) +
                (int)($this->selectedItems[$itemId]['second_semester_quantity'] ?? 0) +
                (int)($this->selectedItems[$itemId]['third_semester_quantity'] ?? 0);
        }
    }

    public function addItem($newItemId)
    {
        if (!$newItemId) {
            return;
        }

        $item = $this->itemsToRequest->find($newItemId);

        if (!$item || array_key_exists($item->id, $this->selectedItems)) {
            $this->search = '';
            $this->showDropdown = false;
            return;
        }


        $this->selectedItems[$item->id] = [
            'name' => $item->name,
            'description' => $item->description ?? '',
            'unit' => $item->unit,
            'first_semester_quantity' => 0,
            'second_semester_quantity' => 0,
            'third_semester_quantity' => 0,
            'total_quantity' => 0,
        ];
        $this->search = '';
        $this->showDropdown = false;
    }

    public function removeItem($itemId)
    {
        unset($this->selectedItems[$itemId]);
    }


    public function saveRequest()
    {
        if ($this->isProcessing) {
            return;
        }

        $this->isProcessing = true;

        $request = AnnualRequest::create([
            'user_id' => Auth::user()->id,
        ]);

        foreach ($this->selectedItems as $itemId => $details) {
            $request->items()->attach(
                $itemId,
                [
                    'quantity' => $details['first_semester_quantity'] + $details['second_semester_quantity'] + $details['third_semester_quantity'],
                    'first_semester_quantity' => $details['first_semester_quantity'],
                    'second_semester_quantity' => $details['second_semester_quantity'],
                    'third_semester_quantity' => $details['third_semester_quantity'],
                ]
            );;
        }

        session()->flash('message', 'تم حفظ الطلب بنجاح');
        $this->isProcessing = false;
        redirect(route('annual-request.index'));
    }

    public function render()
    {
        return view('annual-request.create-livewire');
    }
}

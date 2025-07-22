<?php

namespace App\Livewire\AnnualRequest;

use App\Models\AnnualRequest;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AnnualRequestEdit extends Component
{
    public $annualRequest;
    public $itemsToRequest;
    public $selectedItems = [];
    public $filterdItems;
    public $search = '';
    public $showDropdown = false;

    public function mount($annualRequestId)
    {

        $this->annualRequest = AnnualRequest::with('items')->findOrFail($annualRequestId);
        foreach ($this->annualRequest->items as $item) {
            $this->selectedItems[$item->id] = [
                'name' => $item->name,
                'unit' => $item->unit,
                'description' => $item->description ?? '',
                'first_semester_quantity' => $item->pivot->first_semester_quantity,
                'second_semester_quantity' => $item->pivot->second_semester_quantity,
                'third_semester_quantity' => $item->pivot->third_semester_quantity,
                'total_quantity' => $item->pivot->first_semester_quantity + $item->pivot->second_semester_quantity + $item->pivot->third_semester_quantity,
            ];
        }
        $user = Auth::user();
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
            'unit' => $item->unit,
            'description' => $item->description,
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
        $this->annualRequest->items()->detach($itemId);

        unset($this->selectedItems[$itemId]);
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

    public function saveRequest()
    {
        $this->validate([
            'selectedItems.*.total_quantity' => 'required|integer|min:1',
        ]);
        foreach ($this->selectedItems as $itemId => $details) {
            if ($this->annualRequest->items->contains($itemId)) {
                $this->annualRequest->items()->updateExistingPivot($itemId, [
                    'first_semester_quantity' => (int)$details['first_semester_quantity'] ?? 0,
                    'second_semester_quantity' => (int)$details['second_semester_quantity'] ?? 0,
                    'third_semester_quantity' => (int)$details['third_semester_quantity'] ?? 0,
                    'quantity' => $details['total_quantity']
                ]);
            } else {
                $this->annualRequest->items()->attach($itemId, [
                    'first_semester_quantity' => (int)$details['first_semester_quantity']?? 0,
                    'second_semester_quantity' => (int)$details['second_semester_quantity']?? 0,
                    'third_semester_quantity' => (int)$details['third_semester_quantity']?? 0,
                    'quantity' => $details['total_quantity']
                ]);
            }
        }
        session()->flash('message', 'تم تحديث الطلب بنجاح');
        return redirect()->back();
    }

    public function passRequest()
    {
        $this->saveRequest();
        $this->annualRequest->forwardRequest();
        session()->flash('message', 'تم إرسال طلبك بنجاح');
        return redirect()->route('annual-request.index');
    }

    public function render()
    {
        return view('livewire.annual-request.edit');
    }
}

<?php

namespace App\Livewire;

use App\Models\AnnualRequest;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AnnualRequestEdit extends Component
{
    public $annualRequest;
    public $itemsToRequest;
    public $selectedItems = [];

    public function mount($annualRequestId)
    {

        $this->annualRequest = AnnualRequest::with('items')->findOrFail($annualRequestId);

        foreach ($this->annualRequest->items as $item) {
            $this->selectedItems[$item->id] = [
                'name' => $item->name,
                'description' => $item->description ?? '',
                'quantity' => $item->pivot->quantity,
            ];
        }

        $user = Auth::user();
        $this->itemsToRequest = $user->items();
    }

    public function addItem($newItemId)
    {
        if (!$newItemId) {
            return;
        }

        $item = $this->itemsToRequest->find($newItemId);

        if (!$item || array_key_exists($item->id, $this->selectedItems)) {
            return;
        }

        $this->selectedItems[$item->id] = [
            'name' => $item->name,
            'description' => $item->description ?? '',
            'quantity' => 1,
        ];
    }

    public function removeItem($itemId)
    {
        $this->annualRequest->items()->detach($itemId);

        unset($this->selectedItems[$itemId]);
    }

    public function saveRequest()
    {
        foreach ($this->selectedItems as $itemId => $details) {
            if ($this->annualRequest->items->contains($itemId)) {
                $this->annualRequest->items()->updateExistingPivot($itemId, ['quantity' => $details['quantity']]);
            } else {
                $this->annualRequest->items()->attach($itemId, ['quantity' => $details['quantity']]);
            }
        }

        session()->flash('message', 'تم تحديث الطلب بنجاح');
        // return redirect()->route('annual-request.index');
    }

    public function passRequest(){
        $this->saveRequest();
        $this->annualRequest->forwardRequest();
        session()->flash('message', 'تم إرسال طلبك بنجاح');
        return redirect()->route('annual-request.index');
    }

    public function render()
    {
        return view('annual-request.edit-livewire');
    }
}

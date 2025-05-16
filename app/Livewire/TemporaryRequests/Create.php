<?php

namespace App\Livewire\TemporaryRequests;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Create extends Component
{
    use WithPagination;

    public $UserRequests;
    public $search = '';
    public $showDropdown = false;
    public $filterdItems;
    public $itemsToRequest;
    public $selectedItem;
    public $requestDetails = [
        'quantity' => '',
        'reason' => '',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->itemsToRequest = $user->items();
    }

    public function getFilteredItemsProperty()
    {
        if (!$this->search) {
            return $this->itemsToRequest;
        }
        return $this->itemsToRequest
            ->filter(fn($item) => str_contains(strtolower($item->name), strtolower($this->search)));
    }

    public function selectItem($newItemId)
    {
        if (!$newItemId) {
            return;
        }

        $item = $this->itemsToRequest->find($newItemId);

        if (!$item) {
            $this->search = '';
            $this->showDropdown = false;
            return;
        }

        $this->selectedItem = $item;

        $this->search = '';
        $this->showDropdown = false;
    }

    public function submit()
    {
        $this->validate([
            'selectedItem' => 'required',
            'requestDetails.quantity' => 'required|numeric|min:1',
            'requestDetails.reason' => 'required',
        ], [
            'selectedItem.required' => 'يرجى اختيار المادة',
            'requestDetails.quantity.required' => 'يرجى إدخال كمية',
            'requestDetails.quantity.numeric' => 'يرجى إدخال كمية اكبر من الصفر',
            'requestDetails.reason.required' => 'يرجى توضيح سبب الطلب',
            'requestDetails.quantity.min' => 'يرجى إدخال كمية اكبر من الصفر',
        ]);
        $user = Auth::user();
        $user->temporaryRequests()->create([
            'item_id' => $this->selectedItem->id,
            'quantity' => $this->requestDetails['quantity'],
            'reason' => $this->requestDetails['reason'],
            'state' => 1,
        ]);


        $this->reset(['selectedItem', 'requestDetails']);
    }

    public function render()
    {
        $this->UserRequests = Auth::user()->temporaryRequests;
        return view('livewire.temporary-requests.create');
    }
}

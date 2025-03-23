<?php

namespace App\Livewire\PeriodicRequest;

use App\Models\AnnualRequest;
use App\Models\Item;
use App\Models\PeriodicRequest;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ItemRequest extends Component
{
    use WithPagination;

    public $itemsToShow = [];
    public $canRequest = true;
    public $search = '';
    public $showRequestModal = false;
    public $selectedItem;
    public $allowedQuantity;
    public $quantity;
    public $currentSemester;

    protected $messages = [
        'quantity.max' => 'الكمية العلبا المسموحة للطلب هي  :max'
    ];
    
    public function mount()
    {
        $this->currentSemester = AnnualRequest::getCurrentSemester();
    }

    public function selectItem(Item $item, User $user)
    {
        $user = Auth::user();
        $item = Stock::addStockToItem($item);
        $this->selectedItem = Stock::addAllowedQuantityToRequest($item, $user);
        $this->allowedQuantity = $this->selectedItem->AllowedQuantityToRequest;
        if (PeriodicRequest::where('user_id', $user->id)->where('item_id', $this->selectedItem->id)->whereNotIn('state', [0, -1])->exists()) {
            $this->dispatch('showMessage', "يوجد طلب سابق للمادة قيد الدراسة يرجى الانتظار", 'خطأ');
            return;
        }
        $this->showRequestModal = true;
    }

    public function closeModal()
    {
        $this->showRequestModal = false;
        $this->reset('selectedItem', 'allowedQuantity', 'quantity');
    }
    
    public function updatedQuantity()
    {
        if ($this->quantity > $this->allowedQuantity) {
            $this->quantity = $this->allowedQuantity;
        }
    }

    public function submitQuantity()
    {
        $user = Auth::user();
        $this->validate([
            'quantity' => 'required|integer|min:1|max:' . $this->allowedQuantity,
        ]);
        $itemExists = collect($this->itemsToShow)->contains('id', $this->selectedItem->id);
        if (!$itemExists) {
            $this->dispatch('showMessage', "المادة ليست ضمن المواد المسموح طلبها لهذه الجهة!", 'خطأ');
            return;
        }

        $request = PeriodicRequest::create([
            'user_id' => $user->id,
            'item_id' => $this->selectedItem->id,
            'quantity' => $this->quantity,
            'state' => 0,
        ]);
        $request->forwardRequest();
        
        $this->closeModal();
        $this->dispatch('showMessage', "تم إرسال الطلب", 'عملية ناجحة');
    }

    public function render(User $user)
    {
        $user = Auth::user();
        $annualItems = $user->getActiveRequest();
        $annualItems = Stock::addUserBalances($annualItems);
        $annualItems = Stock::addUserCurrentSemesterConsumed($annualItems);

        $this->itemsToShow = $annualItems->items->filter(function ($item) {
            return str_contains(strtolower($item->name), strtolower($this->search));
        })->values()->all();

        return view('livewire.periodic-request.item-request');
    }
}

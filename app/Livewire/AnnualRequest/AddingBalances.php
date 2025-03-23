<?php

namespace App\Livewire\AnnualRequest;

use App\Models\AnnualRequest;
use App\Models\BufferStock;
use App\Models\Item;
use App\Models\PeriodicRequest;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddingBalances extends Component
{
    public $UserSearchResault;
    public $SelectedUser;
    public $searchUser;
    public $UserItems;
    public $itemsSearch;
    public $ItemsSearchResault;
    public $selectedItem;
    public $maxQuantity;
    public $quantity;
    public $showApplyButton;

    public function updatedSearchUser()
    {
        if ($this->searchUser == '') {
            $this->UserSearchResault = [];
            return;
        }
        $this->UserSearchResault = User::where('role', 'like', '%' . $this->searchUser . '%')->where('type', '>=', 2)->get();
    }

    public function selectUser($id)
    {
        $this->SelectedUser = User::find($id);
        $this->UserItems = $this->SelectedUser->getActiveRequest()->items;
        $this->UserSearchResault = [];
        $this->searchUser = '';
    }

    public function updatedItemsSearch()
    {
        if ($this->itemsSearch == '') {
            $this->ItemsSearchResault = [];
            return;
        }
        $this->ItemsSearchResault = Item::all()->filter(function ($item) {
            return str_contains(strtolower($item->name), strtolower($this->itemsSearch));
        });
    }

    public function selectItem($id)
    {
        $this->resetValidation();
        $this->showApplyButton = false;
        $this->selectedItem = $this->ItemsSearchResault->firstWhere('id', $id);
        $this->selectedItem = Stock::addStockToItem($this->selectedItem);
        $lastReset = AnnualRequest::getLastYearReset();
        $inStockAvailble = $this->selectedItem->inStockAvalible;
        $buffer_quantity = BufferStock::where('item_id', $this->selectedItem->id)->get()->first();
        if ($inStockAvailble < $buffer_quantity->quantity) {
            $this->maxQuantity = $inStockAvailble;
        } else {
            $this->maxQuantity = $buffer_quantity->quantity;
        }

        if ($inStockAvailble == 0) {
            $this->addError('maxQuantity', 'لا يوجد كمية في المستودع');
            $this->reset(['selectedItem', 'maxQuantity', 'ItemsSearchResault', 'itemsSearch', 'quantity']);
            return;
        }
        if ($buffer_quantity->quantity == 0) {
            $this->addError('maxQuantity', 'لا يوجد مخزون احتياطي');
            $this->reset(['selectedItem', 'maxQuantity', 'ItemsSearchResault', 'itemsSearch', 'quantity']);
            return;
        }

        $this->reset(['ItemsSearchResault', 'itemsSearch']);
    }

    public function updatedQuantity()
    {
        $this->resetValidation();
        if ($this->quantity == '') {
            $this->reset(['quantity', 'showApplyButton']);
            return;
        }
        if ($this->quantity > $this->maxQuantity && $this->quantity) {
            $this->addError('quantity', 'على الكمية أن تكون أقل من ' . $this->maxQuantity);
            $this->reset(['quantity', 'showApplyButton']);
            return;
        }

        $this->showApplyButton = true;
    }

    public function submit()
    {
        $this->validate([
            'quantity' => 'required|numeric|min:1|max:' . $this->maxQuantity,
        ]);

        try {
            Stock::addBalance($this->selectedItem, $this->quantity, 'تصريف من المخزون الاحتياطي من قبل ' . Auth::user()->role, $this->SelectedUser);
            PeriodicRequest::create([
                'user_id' => $this->SelectedUser->id,
                'item_id' => $this->selectedItem->id,
                'quantity' => $this->quantity,
                'state' => 2,
            ]);
            $buffer = BufferStock::where('item_id', $this->selectedItem->id)->get()->first();
            
            $buffer->quantity -= $this->quantity;
            $buffer->save();

            $this->reset(['SelectedUser', 'UserItems', 'itemsSearch', 'ItemsSearchResault', 'selectedItem', 'maxQuantity', 'quantity', 'showApplyButton']);
            $this->dispatch('showMessage', 'عملية ناجحة', 'تم الصرف بنجاح');
        } catch (\Throwable $th) {
            $this->dispatch('showMessage', 'خطأ', $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.annual-request.adding-balances');
    }
}

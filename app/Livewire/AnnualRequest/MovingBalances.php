<?php

namespace App\Livewire\AnnualRequest;

use App\Models\Stock;
use App\Models\User;
use Livewire\Component;

use function Laravel\Prompts\error;

class MovingBalances extends Component
{
    public $fromUser;
    public $searchFromUser;
    public $showDropdownFromUser;
    public $FromUserSearchResault;

    public $toUser;
    public $searchToUser;
    public $showDropdownToUser;
    public $ToUserSearchResault;

    public $items;
    public $searchItem;
    public $SearchItemsResault;
    public $selectedItem;
    public $maxQuantity;
    public $quantity;

    public $showApplyButton;

    public function UpdatedSearchFromUser()
    {
        if ($this->searchFromUser != '') {

            $this->FromUserSearchResault = User::where('role', 'like', '%' . $this->searchFromUser . '%')->where('type', '>=', 2)->get();
            $this->showDropdownFromUser = true;
        } else {
            $this->showDropdownFromUser = false;
        }
    }

    public function UpdatedSearchToUser()
    {
        if ($this->searchToUser != '') {

            $this->ToUserSearchResault = User::where('role', 'like', '%' . $this->searchToUser . '%')->where('type', '>=', 2)->whereNot('id', $this->fromUser->id)->get();
            $this->showDropdownToUser = true;
        } else {
            $this->showDropdownToUser = false;
        }
    }

    public function selectFromUser($id)
    {
        $this->fromUser = User::find($id);
        $this->searchFromUser = '';
        $this->showDropdownFromUser = false;
    }

    public function selectToUser($id)
    {
        $this->toUser = User::find($id);
        $this->searchToUser = '';
        $this->showDropdownToUser = false;
    }

    public  function UpdatedSearchItem()
    {
        if ($this->searchItem != '') {
            $this->SearchItemsResault = collect($this->items)->filter(function ($item) {
                return str_contains(strtolower($item->name), strtolower($this->searchItem));
            });
        } else {
            $this->SearchItemsResault = $this->items;
        }
    }

    public function selectItem($id)
    {
        $this->searchItem = '';
        $this->quantity = '';
        $this->selectedItem = $this->items->find($id);
        $this->maxQuantity = Stock::getUserBalance($this->fromUser, $this->selectedItem);
        $this->SearchItemsResault = '';
    }

    public function updatedQuantity()
    {
        if ($this->quantity > $this->maxQuantity) {
            $this->addError('quantity', 'لا يمكن نقل رصيد أكبر من المتوفر');
            $this->showApplyButton = false;
        } else {
            $this->resetValidation('quantity');
            $this->showApplyButton = true;
        }
        if ($this->quantity === "") {
            $this->showApplyButton = false;
        }
    }

    public function submit()
    {
        try {
            Stock::MoveBalance($this->selectedItem, $this->quantity, $this->fromUser, $this->toUser);
            $this->dispatch('showMessage', 'تم نقل الرصيد بنجاح', 'عملية ناجحة');
            $this->reset();
        } catch (\Throwable $th) {
            $this->dispatch('showMessage', 'error', $th->getMessage());
        }
    }

    public function render()
    {
        if ($this->fromUser != null && $this->toUser != null) {
            $fromUserItems = $this->fromUser->getActiveRequest()->items;
            $ToUserItems = $this->fromUser->getActiveRequest()->items;

            $this->items = $fromUserItems->intersect($ToUserItems);
        }
        return view('livewire.annual-request.moving-balances');
    }
}

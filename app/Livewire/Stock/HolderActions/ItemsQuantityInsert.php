<?php

namespace App\Livewire\Stock\HolderActions;

use App\Models\Stock;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class ItemsQuantityInsert extends Component
{
    use WithPagination;
    
    public $search = '';
    public $showQuantityModal = false;
    public $selectedItem = null;
    public $quantity = 1;
    public $details = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function selectItem($itemId)
    {
        $this->selectedItem = Item::find($itemId);
        $this->showQuantityModal = true;
    }

    public function submitQuantity()
    {
        $this->validate([
            'quantity' => 'required|numeric|min:1',
            'details' => 'required|string',
        ]);

        Stock::inStock($this->selectedItem, $this->quantity, $this->details);
        
        $this->showQuantityModal = false;
        $this->reset(['selectedItem', 'quantity']);
    }

    public function closeModal()
    {
        $this->showQuantityModal = false;
        $this->reset(['selectedItem', 'quantity']);
    }

    public function render()
    {
        return view('livewire.stock.holder-actions.items-quantity-insert', [
            'items' => Item::when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->paginate(20)
        ]);
    }
}

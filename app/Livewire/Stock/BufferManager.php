<?php

namespace App\Livewire\Stock;

use App\Models\BufferStock;
use App\Models\Stock;
use Illuminate\Validation\Rules\Exists;
use Livewire\Component;

class BufferManager extends Component
{
    public $list = [];
    public $search = '';
    public $quantities = [];
    
    // Modal related properties
    public $showModal = false;
    public $selectedItem = null;

    public function mount()
    {
        $this->list = BufferStock::with('item.item_group')->get();
        // Initialize quantities array with current values
        foreach ($this->list as $item) {
            $this->quantities[$item->id] = $item->quantity;
        }
    }

    public function updatedQuantities($value, $key)
    {
        if (!$value || $value < 0) {
            $value = 0;
        }
        $bufferId = $key;
        $bufferStock = BufferStock::find($bufferId);
        
        if ($bufferStock) {
            $bufferStock->quantity = $value;
            $bufferStock->save();
        }
    }
    
    public function showItemDetails($itemId)
    {
        $this->selectedItem = BufferStock::with('item.item_group')->find($itemId);
        $inStockAvailble = Stock::addStockToItem($this->selectedItem->item)->inStockAvalible;
        $this->selectedItem->inStockAvailble = $inStockAvailble?? 0;
        $this->selectedItem->first_semester_needed = Stock::getFirstSemesterNeeded($this->selectedItem->item);
        $this->selectedItem->second_semester_needed = Stock::getSecondSemesterNeeded($this->selectedItem->item);
        $this->selectedItem->third_semester_needed = Stock::getThirdSemesterNeeded($this->selectedItem->item);

        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedItem = null;
    }

    public function render()
    {
        if ($this->search) {
            $filtered = BufferStock::with('item.item_group')
                ->whereHas('item', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                })
                ->get();
                
            $this->list = $filtered;
            
            // Update quantities for filtered results
            foreach ($filtered as $item) {
                if (!isset($this->quantities[$item->id])) {
                    $this->quantities[$item->id] = $item->quantity;
                }
            }
        }
        
        return view('livewire.stock.buffer-manager');
    }
}

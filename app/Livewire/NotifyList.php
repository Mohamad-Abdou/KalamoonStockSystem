<?php

namespace App\Livewire;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotifyList extends Component
{
    public $itemBalanceRemovedList = [];

    public function mount()
    {
        $this->itemBalanceRemovedList = Stock::getUnverfirdBalanceRemovedList(Auth::user());
    }
    
    public function markAsSeen($itemId)
    {
        $stockItem = Stock::find($itemId);
        
        if ($stockItem) {
            $stockItem->update(['approved' => true]);
        }
        
    }
    
    public function render()
    {
        $this->itemBalanceRemovedList = Stock::getUnverfirdBalanceRemovedList(Auth::user());
        return view('livewire.notify-list');
    }
}

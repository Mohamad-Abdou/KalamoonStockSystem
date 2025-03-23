<?php

namespace App\Livewire;

use App\Models\AnnualRequest;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotifyList extends Component
{
    public $itemBalanceRemovedList;

    public function mount() 
    {
        // Initialize as a collection
        $this->itemBalanceRemovedList = collect([]);
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
        if (AnnualRequest::getYearState()) {
            $this->itemBalanceRemovedList = collect(Stock::getUnverfirdBalanceRemovedList(Auth::user()));
        } else {
            $this->itemBalanceRemovedList = collect([]);
        }
        return view('livewire.notify-list');
    }
}
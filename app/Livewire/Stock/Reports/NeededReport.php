<?php

namespace App\Livewire\Stock\Reports;

use App\Models\AnnualRequest;
use App\Models\Item;
use App\Models\Stock;
use Livewire\Component;

class NeededReport extends Component
{
    public $searchByNameAndDetails = '';
    public $lastReset;
    public $filterOption = 'all';
    public $yearState;

    public function mount()
    {
        $this->yearState = AnnualRequest::getYearState();
        $this->lastReset = AnnualRequest::getLastYearReset();
    }

    public function render()
    {
        $items = Item::query()
            ->when($this->searchByNameAndDetails, function ($query) {
                return $query->where('name', 'like', '%' . $this->searchByNameAndDetails . '%')
                    ->orWhere('description', 'like', '%' . $this->searchByNameAndDetails . '%');
            })
            ->get()
            ->map(function ($item) {
                $item = Stock::addStockToItem($item);
                $item->needed = Stock::NeededStock($item);
                $item->totalOut = Stock::totalOut($item);
                $item->mainInStock = Stock::mainInStock($item);
                $item->extras = $item->mainInStock - $item->needed < 0 ? 0 : $item->mainInStock - $item->needed;
                $item->remainQuantity = $item->needed - $item->mainInStock + $item->extras < 0 ? 0 : $item->needed - $item->mainInStock + $item->extras;
                return $item;
            })
            ->when($this->filterOption === 'stock', function ($collection) {
                return $collection->where('mainInStock', '>', 0);
            })
            ->when($this->filterOption === 'needed', function ($collection) {
                return $collection->where('needed', '>', 0);
            })
            ->sortByDesc('mainInStock');

        return view('livewire.stock.reports.needed-report', [
            'items' => $items,
        ]);
    }
}

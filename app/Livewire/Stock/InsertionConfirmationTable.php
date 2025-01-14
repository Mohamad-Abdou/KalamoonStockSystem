<?php

namespace App\Livewire\Stock;

use App\Models\AnnualRequest;
use App\Models\Stock;
use Livewire\Component;
use Livewire\WithPagination;

class InsertionConfirmationTable extends Component
{
    use WithPagination;

    public function approve($id)
    {
        $stock = Stock::find($id);
        $stock->update(['approved' => true]);
        $stock->save();
    }

    public function render()
    {
        $inStocks = Stock::where('user_id', 2)->where('in_quantity', '>', 0)->with('item')->paginate(20);

        return view('livewire.stock.insertion-confirmation-table', ['inStocks' => $inStocks]);
    }
}

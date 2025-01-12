<?php

namespace App\Livewire\PeriodicRequest;

use App\Models\AnnualRequest;
use App\Models\Item;
use App\Models\PeriodicRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ItemRequestArchive extends Component
{
    public $item_id;
    public $requests;
    public $item;

    public function mount($item_id)
    {
        $this->item_id = $item_id;
    }

    public function render()
    {
        $user = Auth::user();
        $lastReset = AnnualRequest::getLastYearReset();
        $this->requests = PeriodicRequest::where('created_at', '>=', $lastReset)->where('user_id', $user->id)->where('item_id', $this->item_id)->with('item')->orderBy('created_at', 'desc')->get();
        $this->item = Item::find($this->item_id);
        return view('livewire.periodic-request.item-request-archive');
    }
}

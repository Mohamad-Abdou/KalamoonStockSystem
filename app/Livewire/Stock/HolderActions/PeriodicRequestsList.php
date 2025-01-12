<?php

namespace App\Livewire\Stock\HolderActions;

use App\Models\PeriodicRequest;
use App\Models\Stock;
use Livewire\Component;
use Livewire\WithPagination;

class PeriodicRequestsList extends Component
{
    use WithPagination;

    public function applied($id)
    {
        $request = PeriodicRequest::where('id', $id)->with(['item', 'user'])->first();
        Stock::outStock($request->item, $request->quantity, 'تسليم طلب احتياج دوري ل'.$request->user->role);
        Stock::removeBalance($request->item, $request->quantity, 'طلب احتياج', $request->user);
        $request->state = -1;
        $request->save();
    }

    public function render()
    {
        return view('livewire.stock.holder-actions.periodic-requests-list', [
            'periodicRequests' => PeriodicRequest::with(['item', 'user'])
                ->whereIn('state', [-1, 2])
                ->orderByRaw('CASE WHEN state = 2 THEN 0 ELSE 1 END')
                ->orderBy('updated_at', 'desc')
                ->paginate(10)
        ]);
    }
}

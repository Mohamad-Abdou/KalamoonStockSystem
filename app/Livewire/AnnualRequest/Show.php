<?php

namespace App\Livewire\AnnualRequest;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public $requestItems;
    public $holdWith;
    public $request;

    public function mount($requestItems, $holdWith, $request)
    {
        $this->requestItems = $requestItems;
        $this->holdWith = $holdWith;
        $this->request = $request;
    }


    public function toggleFrozen($requestId, $itemId)
    {
        $request = \App\Models\AnnualRequest::findOrFail($requestId);
        $request->items()->updateExistingPivot($itemId, [
            'frozen' => !$request->items()->find($itemId)->pivot->frozen
        ]);
        
        return redirect()->route('annual-request.show', $requestId);
    }

    public function render()
    {
        return view('livewire.annual-request.show');
    }
}

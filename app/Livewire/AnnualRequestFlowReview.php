<?php

namespace App\Livewire;

use Livewire\Component;

class AnnualRequestFlowReview extends Component
{
    public $annual_request;
    public $return_reason;
    public $previous_annual_request;
    public $objection = [];
    public function mount($annual_request, $previous_annual_request = null)
    {
        $this->annual_request = $annual_request;
        $this->return_reason = $annual_request->return_reason;
        $this->previous_annual_request = $previous_annual_request;

        // Initialize objection array with existing values
        foreach ($this->annual_request->items as $item) {
            $this->objection[$item->pivot->id] = $item->pivot->objection_reason;
        }
    }

    public function updatedObjection($newValue, $itemId)
    {
        $item = $this->annual_request->items()
            ->wherePivot('id', $itemId)
            ->firstOrFail();

        $item->pivot->objection_reason = $newValue;
        $item->pivot->save();
        $previous_annual_request = $this->previous_annual_request;
        $this->annual_request->items->each(function ($item) use ($previous_annual_request) {
            $prev_item = $previous_annual_request?->items->firstWhere('id', $item->id);
            $item->prev = ['quantity' => $prev_item ? $prev_item->pivot->quantity : 0];
        });
        $this->objection[$itemId] = $newValue;
    }

    public function rejectRequest()
    {
        if (!$this->return_reason) {
            $this->dispatch('showMessage', 'يجب إدخال سبب الإرجاع', 'تنبيه');
            return;
        }
        $this->annual_request->update(['return_reason' => $this->return_reason]);
        $this->annual_request->backwordRequest();
        session()->flash('message', 'تم إرجاع الطللب بنجاح');
        return redirect()->route('annual-request-flow.index');
    }

    public function passRequest()
    {

        $this->annual_request->forwardRequest();
        session()->flash('message', 'تم تحويل الطللب بنجاح');;
        return redirect()->route('annual-request-flow.index');
    }

    public function render()
    {
        return view('livewire.annual-request-flow-review');
    }
}

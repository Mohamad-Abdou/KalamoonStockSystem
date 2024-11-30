<?php

namespace App\Livewire;

use Livewire\Component;

class AnnualRequestFlowReview extends Component
{
    public $annual_request;
    public $return_reason;
    public $previous_annual_request;

    public function mount($annual_request, $previous_annual_request = null)
    {
        $this->annual_request = $annual_request;
        $this->return_reason = $annual_request->return_reason;
        $this->previous_annual_request = $previous_annual_request;
    }

    public function toggleObjection($itemId)
    {
        $item = $this->annual_request->items()->find($itemId);
        $item->pivot->objected = !$item->pivot->objected;
        $item->pivot->save();
    }

    public function rejectRequest(){
        if(!$this->return_reason) {
            $this->dispatch('showMessage', 'يجب إدخال سبب الإرجاع', 'تنبيه');
            return;
        }
        $this->annual_request->update(['return_reason' => $this->return_reason]);
        $this->annual_request->backwordRequest();
        session()->flash('message', 'تم إرجاع الطللب بنجاح');
        return redirect()->route('annual-request-flow.index');
    }

    public function passRequest(){
        
        $this->annual_request->forwardRequest();
        session()->flash('message', 'تم تحويل الطللب بنجاح');;
        return redirect()->route('annual-request-flow.index');
    }

    public function render()
    {
        return view('livewire.annual-request-flow-review');
    }
}

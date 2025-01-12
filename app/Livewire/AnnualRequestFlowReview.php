<?php

namespace App\Livewire;

use App\Models\Stock;
use Livewire\Component;
use PhpParser\Node\Expr\FuncCall;

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

        if ($this->previous_annual_request) {
            $this->linkPrevious();
        }
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
        if ($this->previous_annual_request) {
            $this->linkPrevious();
        }
        $this->objection[$itemId] = $newValue;
    }
    private function linkPrevious()
    {

        $previous_annual_request = Stock::addUserYearConsumed($this->previous_annual_request);
        $this->annual_request->items->each(function ($item) use ($previous_annual_request) {
            $prev_item = $previous_annual_request?->items->firstWhere('id', $item->id);
            $item->prev = [
                'consumed' => $prev_item ? (int)$prev_item->consumed : 0,
                'quantity' => $prev_item ? $prev_item->pivot->quantity : 0
            ];
        });
    }

    public function rejectRequest()
    {
        // Validate return reason with custom error message
        $this->validate([
            'return_reason' => 'required|string|min:3'
        ], [
            'return_reason.required' => 'يجب إدخال سبب الإرجاع'
        ]);

        $this->annual_request->update(['return_reason' => $this->return_reason]);
        $this->annual_request->backwordRequest();
        session()->flash('message', 'تم إرجاع الطلب بنجاح');
        return redirect()->route('annual-request-flow.index');
    }

    public function passRequest()
    {
        try {
            // Move request forward in workflow
            $this->annual_request->forwardRequest();

            // Show success message and redirect
            session()->flash('message', 'تم تحويل الطلب بنجاح');
            return redirect()->route('annual-request-flow.index');
        } catch (\Exception $e) {
            // Handle any errors during the process
            $this->dispatch('showMessage', $e->getMessage(), 'خطأ');
        }
    }


    public function render()
    {
        if ($this->previous_annual_request) {

            $this->linkPrevious();
        }
        return view('livewire.annual-request-flow-review');
    }
}

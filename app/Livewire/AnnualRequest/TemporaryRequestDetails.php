<?php

namespace App\Livewire\AnnualRequest;

use App\Models\TemporaryRequest;
use Livewire\Component;

class TemporaryRequestDetails extends Component
{
    public $temporaryRequest;

    public function mount($requestId = null)
    {
        if ($requestId) {
            $this->temporaryRequest = TemporaryRequest::with(['user', 'item'])->find($requestId);
        }
    }

    public function render()
    {
        return view('livewire.annual-request.temporary-request-details');
    }
}

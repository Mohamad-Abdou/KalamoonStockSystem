<?php

namespace App\Livewire\PeriodicRequestFlow;

use App\Models\AnnualRequest;
use App\Models\BufferStock;
use App\Models\PeriodicRequest;
use App\Models\Stock;
use App\Models\TemporaryRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FlowList extends Component
{
    public $RequestsList;
    public $TemporaryRequestsList;

    public $showDetailsModal;
    public $showRejectionModal;
    public $rejcetedRequestId;
    public $rejection_reason;

    public $showTemporaryRejectionModal;
    public $showTemporaryCheckModal;
    public $temporaryRejectedRequestId;
    public $temporaryRejection_reason;
    public $temporarySelectedRequest;

    public $showEditModal;
    public $editRequest;
    public $oldQuantity;
    public $newQuantity;

    public $currentSemester;

    public $selectedItem;
    public $selectedItemDetails = [
        'stock' => 0,
        'requestQuantity' => 0,
        'userConsumed'  => 0,
        'userRequested' => 0,
    ];

    public function showRejectionModalButton($id)
    {
        $this->rejcetedRequestId = $id;
        $this->showRejectionModal = true;
    }

    public function showEditModalButton($id)
    {
        $this->editRequest = PeriodicRequest::find($id);
        $this->newQuantity = $this->editRequest->quantity;
        $this->showEditModal = true;
    }

    public function editAndPass()
    {

        $this->validate([
            'newQuantity' => ['required', 'numeric', 'min:1', 'max:' . $this->editRequest->quantity],
        ], [
            'newQuantity.max' => 'يسمح بتقليل الكمية فقط'
        ]);
        $this->editRequest->quantity = $this->newQuantity;
        $this->editRequest->save();
        $this->acceptRequest($this->editRequest->id);
        $this->closeModal();
    }


    public function rejectRequest()
    {
        $this->validate([
            'rejection_reason' => 'required|min:1',
        ]);

        $user = Auth::user();
        $this->rejection_reason = 'تم الرفض من قبل ' . $user->role . ' بسبب : ' . $this->rejection_reason;

        $request = PeriodicRequest::find($this->rejcetedRequestId);
        $request->rejection_reason = $this->rejection_reason;
        $request->save();
        $request->rejectRequest();
        $this->closeModal();
    }

    public function acceptRequest($id)
    {
        $request = PeriodicRequest::find($id);
        $request->forwardRequest();
    }


    public function showDetails($id)
    {
        $request = PeriodicRequest::where('id', $id)->with(['user', 'item'])->first();
        $user = User::find($request->user_id);
        $this->selectedItemDetails['requestQuantity'] = $request->quantity;
        $this->selectedItem = $request->item;
        $this->selectedItem = Stock::addStockToItem($this->selectedItem);
        $this->selectedItemDetails['stock'] = $this->selectedItem->inStockAvalible;
        $annual_request = $user->getActiveRequest();
        $annual_request = Stock::addUserYearConsumed($annual_request);
        $this->selectedItemDetails['userConsumed'] = $annual_request->items->firstWhere('id', $this->selectedItem->id)->consumed;
        $this->selectedItemDetails['userRequested'] = $annual_request->items->firstWhere('id', $this->selectedItem->id)->pivot->quantity;
        $this->showDetailsModal = true;
    }

    public function showTemporaryRejectionModalButton($id)
    {
        $this->temporaryRejectedRequestId = $id;
        $this->showTemporaryRejectionModal = true;
    }

    public function showTemporaryCheckModalButton($id)
    {
        $this->temporarySelectedRequest = TemporaryRequest::with(['user', 'item'])->find($id);
        
        $this->selectedItem = $this->temporarySelectedRequest->item;
        $this->selectedItem = Stock::addStockToItem($this->selectedItem);
        
        $annual_request = $this->temporarySelectedRequest->user->getActiveRequest();
        
        $this->selectedItem = BufferStock::with('item.item_group')->find($this->selectedItem->id);
        
        $inStockAvailble = Stock::addStockToItem($this->selectedItem->item)->inStockAvalible;
        $this->selectedItem->inStockAvailble = $inStockAvailble ?? 0;
        
        $this->selectedItem->first_semester_needed = Stock::getFirstSemesterNeeded($this->selectedItem->item);
        $this->selectedItem->second_semester_needed = Stock::getSecondSemesterNeeded($this->selectedItem->item);
        $this->selectedItem->third_semester_needed = Stock::getThirdSemesterNeeded($this->selectedItem->item);
        
        $this->currentSemester = AnnualRequest::getCurrentSemester();
        $this->selectedItemDetails['NeededStockForSemester'] = Stock::SemesterNeededStock($this->selectedItem->item);
        
        if ($annual_request) {
            $annual_request = Stock::addUserYearConsumed($annual_request);
            $this->selectedItemDetails['userConsumed'] = $annual_request->items->firstWhere('id', $this->selectedItem->id)?->consumed ?? 0;
            $this->selectedItemDetails['userRequested'] = $annual_request->items->firstWhere('id', $this->selectedItem->id)?->pivot?->quantity ?? 0;
        } else {
            $this->selectedItemDetails['userConsumed'] = 0;
            $this->selectedItemDetails['userRequested'] = 0;
        }
        
        $this->showTemporaryCheckModal = true;
    }

    public function rejectTemporaryRequest()
    {
        $this->validate([
            'temporaryRejection_reason' => 'required|min:1',
        ]);

        $user = Auth::user();
        $this->temporaryRejection_reason = 'تم الرفض من قبل ' . $user->role . ' بسبب : ' . $this->temporaryRejection_reason;

        $request = TemporaryRequest::find($this->temporaryRejectedRequestId);
        $request->rejection_reason = $this->temporaryRejection_reason;
        $request->state = -1;
        $request->save();
        $this->closeModal();
    }

    public function acceptTemporaryRequest()
    {
        $request = TemporaryRequest::find($this->temporarySelectedRequest->id);
        $request->state = 2;
        $request->save();
        $this->closeModal();
    }

    public function goToBalances()
    {
        return redirect()->route('annual-request.balanes', ['request' => $this->temporarySelectedRequest->id]);
    }

    public function closeModal()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function render()
    {
        $user = Auth::user();
        $this->RequestsList = PeriodicRequest::with(['item', 'user'])->where('state', $user->id)->orderBy('created_at')->get();
        $this->TemporaryRequestsList = TemporaryRequest::with(['item', 'user'])->where('state', 1)->orderBy('created_at')->get();
        return view('livewire.periodic-request-flow.flow-list');
    }
}

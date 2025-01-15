<?php

namespace App\Livewire\PeriodicRequestFlow;

use App\Models\PeriodicRequest;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FlowList extends Component
{
    public $RequestsList;
    public $showDetailsModal;
    public $showRejectionModal;
    public $rejcetedRequestId;
    public $rejection_reason;

    public $showEditModal;
    public $editRequest;
    public $oldQuantity;
    public $newQuantity;

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
        ],[
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
        $this->closeReqjectionMoadl();
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

    public function closeModal()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function render()
    {
        $user = Auth::user();
        $this->RequestsList = PeriodicRequest::with(['item', 'user'])->where('state', $user->id)->orderBy('created_at')->get();
        return view('livewire.periodic-request-flow.flow-list');
    }
}

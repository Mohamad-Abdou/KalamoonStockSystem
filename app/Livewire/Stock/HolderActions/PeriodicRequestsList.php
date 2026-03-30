<?php

namespace App\Livewire\Stock\HolderActions;

use App\Models\PeriodicRequest;
use App\Models\Stock;
use App\Models\TemporaryRequest;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PeriodicRequestsList extends Component
{
    use WithPagination;

    public $filterOption = 0;
    public $users;
    public $selectedUser = null;
    public $WaitingRequests = true;
    
    public function mount()
    {
        $this->users = User::where('type', '>=', 2)->get();
    }

    public function toggleWaitingRequests()
    {
        $this->WaitingRequests = !$this->WaitingRequests;
    }

    public function filterByUser($userId)
    {
        if ($userId == 0) {
            $this->selectedUser = null;
            $this->resetPage();
            return;
        }
        $this->selectedUser = $userId;
        $this->resetPage();
    }

    public function applied($id)
    {
        if ($this->filterOption == 0) {
            $request = PeriodicRequest::where('id', $id)->with(['item', 'user'])->first();
            Stock::outStock($request->item, $request->quantity, 'تسليم طلب احتياج دوري ل' . $request->user->role);
            Stock::removeBalance($request->item, $request->quantity, 'طلب احتياج', $request->user);
            $request->state = -1    ;
            $request->save();
        } elseif ($this->filterOption == 1) {
            $request = TemporaryRequest::where('id', $id)->with(['item', 'user'])->first();
            Stock::outStock($request->item, $request->quantity, 'تسليم طلب احتياج غير مخطط له ل' . $request->user->role);
            $request->state = -2;
            $request->save();
        }
    }
    public function render()
    {
        $preiodicRequests = PeriodicRequest::with(['item', 'user'])
        ->whereIn('state', [-1, 2])
        ->where('user_id', $this->selectedUser ? $this->selectedUser : '!=', null)
        ->orderByRaw('CASE WHEN state = 2 THEN 0 ELSE 1 END')
        ->orderBy('updated_at', 'desc');

        if ($this->WaitingRequests) {
            $preiodicRequests = $preiodicRequests->where('state', 2);
        }
        
        $temporaryRequests = TemporaryRequest::with(['item', 'user'])
        ->whereIn('state', [-2, 2])
        ->where('user_id', $this->selectedUser ? $this->selectedUser : '!=', null)
        ->orderByRaw('CASE WHEN state = 2 THEN 0 ELSE 1 END')
        ->orderBy('updated_at', 'desc');
        
        if ($this->WaitingRequests) {
            $temporaryRequests = $temporaryRequests->where('state', 2);
        }

        if ($this->filterOption == 0) {
            $listToShow = $preiodicRequests->paginate(20);
        } elseif ($this->filterOption == 1) {
            $listToShow = $temporaryRequests->paginate(20);
        }
        
        return view('livewire.stock.holder-actions.periodic-requests-list', [
            'listToShow' => $listToShow,
        ]);
    }
}

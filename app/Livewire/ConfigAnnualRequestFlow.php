<?php

namespace App\Livewire;

use App\Models\RequestFlow;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ConfigAnnualRequestFlow extends Component
{
    public $TheFlow;
    public $search = '';
    public $searchResults = [];

    public function mount()
    {
        $this->TheFlow = RequestFlow::where('request_type', 0)->orderBy('order')->with('user')->get();
    }

    public function updatedSearch()
    {
        if (strlen($this->search) > 2) {
            $this->searchResults = User::where('role', 'like', "%{$this->search}%")
                ->take(5)
                ->get();
        }
    }

    public function updateSort($flowId, $newOrder)
    {
        $flow = RequestFlow::find($flowId);
        $currentOrder = $flow->order;
        $requestType = $flow->request_type;

        $newOrder += 1;

        if ($currentOrder === $newOrder) return;

        DB::transaction(function () use ($flow, $currentOrder, $newOrder, $requestType) {
            $flow->update(['order' => 0]);

            $query = RequestFlow::where('request_type', $requestType);

            if ($currentOrder < $newOrder) {
                $query->whereBetween('order', [$currentOrder + 1, $newOrder])
                    ->decrement('order');
            } else {
                $query->whereBetween('order', [$newOrder, $currentOrder - 1])
                    ->increment('order');
            }

            $flow->update(['order' => $newOrder]);
        });

        $this->TheFlow = RequestFlow::where('request_type', $requestType)
            ->orderBy('order')
            ->with('user')
            ->get();
    }
    public function addToFlow($userId)
    {
        if ($this->TheFlow->contains('user_id', $userId)) {
            $this->dispatch('showMessage', 'المستخدم موجود مسبقاً في التدفق', 'تنبيه');
            return;
        }

        $maxOrder = $this->TheFlow->max('order') ?? 0;

        RequestFlow::create([
            'user_id' => $userId,
            'request_type' => 0,
            'order' => $maxOrder + 1
        ]);

        $this->TheFlow = RequestFlow::where('request_type', 0)->orderBy('order')->with('user')->get();
        $this->search = '';
        $this->searchResults = [];
    }

    public function deleteFromFlow($id)
    {
        $NodeToDelete = RequestFlow::find($id);
        $order = $NodeToDelete->order;

        DB::transaction(function () use ($NodeToDelete, $order) {
            RequestFlow::where('request_type', 0)->where('order', '>', $order)->decrement('order');
            $NodeToDelete->delete();
        });

        $this->TheFlow = RequestFlow::where('request_type', 0)->orderBy('order')->with('user')->get();
    }

    public function render()
    {
        return view('admin.config-annual-request-flow');
    }
}

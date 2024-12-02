<?php

namespace App\Livewire;

use App\Models\AnnualRequest;
use Livewire\Component;
use Livewire\WithPagination;

class AnnualRequestArchive extends Component
{
    use WithPagination;
    public $annual_requests = [];
    public $search = '';
    public $filterState = 'all';
    public $dateFrom = '';
    public $dateTo = '';

    public function mount()
    {
        $this->loadAnnualRequests();
    }

    public function updatedSearch()
    {
        $this->loadAnnualRequests();
    }

    public function updatedFilterState()
    {
        $this->loadAnnualRequests();
    }

    public function updatedDateFrom()
    {
        $this->loadAnnualRequests();
    }

    public function updatedDateTo()
    {
        $this->loadAnnualRequests();
    }

    private function loadAnnualRequests()
    {
        $query = AnnualRequest::query();
        switch ($this->filterState) {
            case '2':
                $query->where('state', 2);
                break;
            case '-1':
                $query->where('state', -1);
                break;
            case 'study':
                $query->where('state', '!=', 2)->where('state', '!=', -1);
                break;
        }
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('role', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $this->annual_requests = $query->orderBy('created_at', 'DESC')->get();
    }

    public function resetFilters()
    {
        $this->filterState = 'all';
        $this->search = '';
        $this->dateFrom = null;
        $this->dateTo = null;
        $this->loadAnnualRequests();
    }

    public function render()
    {
        return view('annual-request.annual-request-archive');
    }
}
